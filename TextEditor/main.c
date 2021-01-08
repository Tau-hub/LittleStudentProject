#define _GNU_SOURCE
#include <termios.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <unistd.h>
#include <poll.h>
#include <sys/wait.h>
#include <signal.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <sys/ioctl.h>
#include "struct.h"
#include "input.h"

// used to free  grid editor
void tofree(Editor *editor)
{
    for (int i = 0; i < GRIDROW; i++)
    {
        free(editor->grid[i]);
    }
    free(editor->grid);
    free(editor->size);
}

// to clear the whole screen
void clear()
{
    write(STDOUT_FILENO, "\x1b[2J", 5);
}

// initialiaze buffer if a parameter is given
int initBuffer(Editor *editor, char *name)
{
    ssize_t nbread;
    char *buffer = malloc(sizeof(char) * BUFSIZ);
    int filein = open(name, O_RDWR);
    if (filein == -1)
    {
        SetCursorPosition(0, 0);
        printf("File doesn't exist, exiting...\n");
        exit(EXIT_FAILURE);
    }
    int row = 0;
    int column = 0;
    while ((nbread = read(filein, buffer, BUFSIZ)) > 0)
    {
        for (int i = 0; i < nbread; i++)
        {
            if (buffer[i] == '\n')
            {
                editor->grid[row][column] = buffer[i];
                editor->size[row]++;
                column = 0;
                row++;
            }
            else
            {
                editor->size[row]++;
                editor->grid[row][column] = buffer[i];
                column++;
            }
        }
    }
    close(filein);
    free(buffer);
    return 0;
}


int input(Editor *editor, Windows *windows, char *buffer)
{
    switch (editor->type)
    {
    case NORMAL:
        input_normal(editor, windows, *buffer);
        break;
    case REMPLACEMENT:
        input_remplacement(editor, windows, *buffer);
        break;
    case INSERTION:
        input_insertion(editor, windows, *buffer);
        break;
    case COMMANDE:
        return input_commande(editor, windows, *buffer);
        break;
    default:
        break;
    }
    return 1;
}
int main(int argc, char *argv[])
{
    clear();
    struct termios p;
    struct termios old;
    tcgetattr(STDIN_FILENO, &old);
    cfmakeraw(&p);
    struct pollfd fds[2];
    int mice = open("/dev/input/mice", O_RDWR);
    if (mice == -1)
    {
        printf("Mouse doesn't work, maybe you should check your rights....\n Exiting...\n");
        exit(EXIT_FAILURE);
    }
    fds[1].fd = mice;
    fds[0].fd = STDIN_FILENO;
    fds[0].events = fds[1].events = POLLIN;
    Editor editor;
    Windows windows = new_windows();
    Mouse mouse = new_mouse();
    if (argc > 1)
    {
        editor = new_editor(argv[1]);
        initBuffer(&editor, argv[1]);
    }
    else
        editor = new_editor(NULL);
    tcsetattr(STDIN_FILENO, TCSANOW, &p);
    refresh_windows(&windows, &editor);
    char *buffer = malloc(1024);
    ssize_t nbread = 0;
    while (1)
    {
        int r = poll(fds, 2, -1);
        if (r > 0)
        {
            if (fds[0].revents & POLLIN)
            {
                nbread = read(fds[0].fd, buffer, 1024);
                buffer[nbread] = '\0';
                if (!strcmp(buffer, ARROW_DOWN) || !strcmp(buffer, ARROW_UP) || !strcmp(buffer, ARROW_RIGHT) || !strcmp(buffer, ARROW_LEFT))
                    arrow_keys(&editor, &windows, buffer);
                else
                {
                    if(!strcmp(buffer,DEL))
                    {
                        *buffer = BACKSPACE;
                    }
                    if (!input(&editor, &windows, buffer))
                    {
                        break;
                    }
                }
                if ((editor.type != COMMANDE || commande[0] != ':')  && commande[0] != '/')
                    refresh_windows(&windows, &editor);
            }
            if (fds[1].revents & POLLIN)
            {
                nbread = read(fds[1].fd, buffer, 1024);
                buffer[nbread] = '\0';
                if (!input_mouse(&windows, &editor, &mouse, buffer))
                    refresh_windows(&windows, &editor);
            }
        }
    }
    free(buffer);
    tcsetattr(STDIN_FILENO, TCSANOW, &old);
    tofree(&editor);
    SetCursorPosition(0, 0);
    clear();
    return 0;
}