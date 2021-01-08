#define _GNU_SOURCE 
#include <signal.h>
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <string.h>
#include <sys/types.h>
#include <sys/types.h>
#include <sys/wait.h>
#include <fcntl.h>

// On définira le maximum de processus à 1024
typedef struct
{
  pid_t pid[1024];
  int taille;
  int dead[1024];
} sleep_t;

sleep_t pids;
volatile sig_atomic_t g_signum = 0;
static struct sigaction act;

void sig_stp(int signum)
{
  g_signum = signum;
}
void affiche_cmd(char* argv[])
{
    if(argv[0] == NULL) { write(STDOUT_FILENO,"argv[0] null",strlen("argv[0] null")); return exit(EXIT_FAILURE); }
    for(int i = 0; argv[i] != NULL; i++)
    {
        char* buffer = malloc(sizeof(char)*1024);
        if(argv[i+1] == NULL) 
        {
          sprintf(buffer,"%d : %s\n",i, argv[i]);
        }
        else
        {
          sprintf(buffer, " %d : %s \n",i, argv[i]);   
        }
        write(STDOUT_FILENO,buffer,strlen(buffer));
        free(buffer);
    }
}

int simple_cmd(char* argv[])
{
  if(argv[0]== NULL) return 1;
  pid_t new_p;
  if(!strcmp(argv[0], "cd"))
  {
   if(argv[1] == NULL) 
   {
     return 1;
   }
   int fd = chdir(argv[1]);
   if(fd == -1) write(STDOUT_FILENO,"Directory not found\n",strlen("Directory not found\n"));
   return 1;
  }
  else if(!strcmp(argv[0],"exit"))
  {
    return 0;
  }
  else
  {
     new_p = fork();
     if(new_p == 0)
     {
       execvp(argv[0], argv);
       return 0;
     }
     while(1)
     {
       int ret = waitpid(new_p,NULL,WNOHANG);
       if(g_signum == SIGTSTP || ret == new_p)
       {
         if(g_signum == SIGTSTP)
         {
           if(pids.taille != 1024)
           {
              pids.dead[pids.taille] = 1;
              pids.pid[pids.taille] = new_p;
              pids.taille++;
              char *buffer = malloc(1024);
              sprintf(buffer, "Processus : %d name : %s  \n", pids.taille-1, argv[0]);
              write(STDOUT_FILENO,buffer,strlen(buffer));
              free(buffer);
           }
           else write(STDOUT_FILENO,"Trop de processus endormi\n", strlen("Trop de processus endormi"));
         }
         break;
       }
     }
     return 1;
  }
}

int redir_cmd(char *argv[], char *in , char *out)
{
  int dupstdout;
  int dupstdin;
  int fdin;
  int fdout;
  int n;
  if(in != NULL)
  {
    fdin = open(in, O_RDONLY);
    dupstdin = dup(STDIN_FILENO);
    dup2(fdin, STDIN_FILENO);
  } 
  if(out != NULL)
  {
    fdout = open(out, O_WRONLY|O_CREAT, 0644);
    dupstdout = dup(STDOUT_FILENO);
    dup2(fdout, STDOUT_FILENO);
  }
  n = simple_cmd(argv);
  if(in != NULL){ close(fdin); dup2(dupstdin, STDIN_FILENO); } 
  if(out != NULL){ close(fdout); dup2(dupstdout, STDOUT_FILENO); }
  if(!n) return 0;
  else return 1;
}
int env_check(char* token)
{
  for(unsigned int i = 0 ; i < strlen(token); i++)
  {
      if(token[i] == '=')
      {
        char *sname = strtok(token,"=");
        char *svalue = strtok(NULL, " ");
        setenv(sname,svalue,-1);
        return 0;
      }
  }
  return 1;
}

int sleep_check(char *s)
{
 if(strlen(s)>=4)
 {
   if((s[0]=='f' || s[0]=='b') && s[1]== 'g' && s[2] == ' ')
   {
      char* token = strtok(s+2, " ");
      int number = atoi(token);
      if(pids.taille <= number)
      {
        write(STDOUT_FILENO,"Tâche inexistante\n", strlen("Tâche inexistante\n"));
        return 0;
      }
      if(s[0] == 'f')
      {
        kill(pids.pid[number],SIGCONT);
        if(pids.dead[number] != 0)
        while(1)
        {
          
          {
            int ret = waitpid(pids.pid[number],NULL,WNOHANG);
            if(ret == pids.pid[number] || g_signum == SIGTSTP)
            {
              if(ret == pids.pid[number]) pids.dead[number] = 0;
              break;
            }
          }
        }
        else write(STDOUT_FILENO,"Tâche inexistante\n",strlen("Tâche inexistante\n")); 
      }
      else if(s[0] == 'b')
      {
       if(pids.dead[number] != 0)
       kill(pids.pid[number],SIGTSTP);
       else write(STDOUT_FILENO,"Tâche inexistante\n",strlen("Tâche inexistante\n")); 
      }
      return 0;
   }
 }
 return 1;
}

int parse_line_pipes(char*s,char***argv[],char**in,char**out)
{
  *in = NULL;
  *out = NULL;
  int sleep = 1;
  int l = 0;
  int i = 0;
  if(s[0] == '#')
  {
    write(STDOUT_FILENO,"Votre commande ne contient qu'un commentaire", strlen("Votre commande ne contient qu'un commentaire"));
    return -1;
  } 
  s = strtok(s, "#");
  if(s!= NULL)
  sleep = sleep_check(s);
  char* token = strtok(s, " ");
  if(token != NULL)
  if(!env_check(token) || !sleep) token = NULL;
  (*argv)[i][l++] = token;
  while(token != NULL)
  {
    token = strtok(NULL, " ");
    if(token != NULL)
    {
      if(!strcmp(token,"<"))
      {
       token = strtok(NULL, " ");
       if(token == NULL) break;
       *in = token;
      } 
      else if(!strcmp(token,">"))
      {
       token = strtok(NULL, " ");
       if(token == NULL) break;
       *out = token;
      }
      else if(!strcmp(token, "|"))
      {
        (*argv)[i][l] = NULL;
        i++;
        l = 0;
      }
      else
      {
          if(token[0] == '$' )
          {
            if(getenv(token+1) != NULL)
            {
              token = getenv(token+1);
              (*argv)[i][l] = token;
              l++;
            }             
          } 
          else
          {
            (*argv)[i][l] = token;
            l++;
          }
      }   
    }
    if(i!= 0 || l != 0)(*argv)[i+1][0] = NULL;
    if(argv[0][0] != NULL) (*argv)[i][l] = NULL;
  }
  return 0;
}

int pipe_cmd(char **argv[], char *in , char *out)
{
  if(argv[0][0] == NULL) return 1;
  if(argv[1][0] == NULL) return redir_cmd(*argv, in, out);
  int pipefd[2]; int bufferfd; int oldstdin = dup(STDIN_FILENO); int oldstdout = dup(STDOUT_FILENO);
  int i = 0;
  while(argv[i][0]!=NULL)
  {
    pipe(pipefd);
    if(argv[i+1][0]== NULL) dup2(oldstdout, STDOUT_FILENO);
    else dup2(pipefd[1], STDOUT_FILENO);
    if(i != 0) dup2(bufferfd,STDIN_FILENO);
    if(in != NULL) { redir_cmd(argv[i],in,out); in = NULL; }
    else if(out != NULL && argv[i+1][0] == NULL) { redir_cmd(argv[i], in, out); out = NULL; }
    else simple_cmd(argv[i]);
    close(pipefd[1]);
    bufferfd = dup(pipefd[0]);
    i++;
  }
  dup2(oldstdin, STDIN_FILENO);
  return 1;
}
int main(int argc, char* argv[])
{
    pids.taille = 0;
    act.sa_handler = sig_stp;
    sigemptyset(&act.sa_mask);
    act.sa_flags = SA_RESTART;
    sigaction(SIGTSTP,&act,NULL);
    int script = 0;
    char *in;
    char *out;
    char*** argv2 = malloc(sizeof(char**)*10);
    for(int i = 0 ; i < 10 ; i++)
    {
      argv2[i] = malloc(sizeof(char*)*100);
    }
    char cmd[1024];
    char* cwd =  malloc(sizeof(char)*1024);
    if(argc > 1)
    {  
       int fd = open(argv[1], O_RDONLY);
       if(fd == -1) { write(STDOUT_FILENO,"Fichier introuvable\n",strlen("Fichier introuvable\n")); exit(EXIT_FAILURE);}
       ssize_t snbread;
       char buffer[1024];
       int i = 0;
       int j = 0;
       off_t endpos = lseek(fd,0, SEEK_END);
       off_t pos = lseek(fd,0,SEEK_SET);
       while(pos < endpos)
       {
         snbread = read(fd,buffer,1024);
         for(i = 0 ; buffer[i] != '\n' && i < snbread; i++) { }
         j += i+1;
         char *bufferbis = malloc(sizeof(char)*i);
         strncpy(bufferbis,buffer,i);
         bufferbis[i-1] = '\0';
         parse_line_pipes(bufferbis,&argv2,&in,&out);
         pos = lseek(fd,j,SEEK_SET);
         if(!pipe_cmd(argv2,in,out)) 
         {
           free(bufferbis);
           break;
         }
         free(bufferbis);
       }
       script = 1;
    }
    while(!script)
    {
     getcwd(cwd, 1024);
     write(STDOUT_FILENO, cwd, strlen(cwd));
     write(STDOUT_FILENO," -> ", strlen(" -> "));
     ssize_t nbread = read(STDIN_FILENO,cmd,1024);
     if(nbread == 0) break;      
     cmd[nbread-1] = '\0';
     g_signum = 0;
     if(!parse_line_pipes(cmd,&argv2,&in,&out))
     if(!pipe_cmd(argv2,in,out)) break;
    }
    for(int i = 0 ; i < pids.taille ; i++) kill(pids.pid[i], SIGTSTP);
    free(cwd);
    for(int i = 0 ; i < 10 ; i++)
    free(argv2[i]);
    free(argv2);
    return 0;
}

//old parse_line

/*int parse_line(char *s, char** argv[])
{
  int l = 0;
  if(s[0] == '#')
  {
    printf("Votre commande ne contient qu'un commentaire.\n");  
    return -1;
  } 
  s = strtok(s, "#");
  char* token = strtok(s, " ");
  (*argv)[l] = token;
  while(token != NULL)
  {
    l++;
    token = strtok(NULL, " ");
    (*argv)[l] = token;
  }
  return 0;
}*/


/*int parse_line_redir(char *s, char **argv [], char **in , char **out)
{
  *in = NULL;
  *out = NULL;
  int l = 0;
  if(s[0] == '#')
  {
    printf("Votre commande ne contient qu'un commentaire.\n");  
    return -1;
  } 
  s = strtok(s, "#");
  char* token = strtok(s, " ");
  (*argv)[l] = token;
  while(token != NULL)
  {
    token = strtok(NULL, " ");
    if(token != NULL)
    {
      if(!strcmp(token,"<"))
      {
       token = strtok(NULL, " ");
       if(token == NULL) break;
       *in = token;
      } 
      else if(!strcmp(token,">"))
      {
       token = strtok(NULL, " ");
       if(token == NULL) break;
       *out = token;
      }
      else
      {
       l++;
       (*argv)[l] = token;
      }   
    }
  }
  (*argv)[l+1] = NULL;
  return 0;
}*/