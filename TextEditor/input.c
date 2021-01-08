#include "input.h"


int save(Editor *editor)
{
    int fd = open(editor->name, O_CREAT | O_WRONLY | O_TRUNC, 0755);
    for (int i = 0; i < GRIDROW; i++)
    {
        for (int j = 0; j < editor->size[i]; j++)
        {
            write(fd, &editor->grid[i][j], 1);
        }
    }
    close(fd);
    return 0;
}

//Give information about the line, column of the editor and its mode
int info(Editor *editor, Windows *windows)
{
    char buffer[1024];
    SetCursorPosition(windows->rowmax, 0);
    sprintf(buffer, "\x1b[K");
    write(STDOUT_FILENO, buffer, strlen(buffer));
    sprintf(buffer, "Ln : %d , Col : %d", editor->row, editor->column);
    int cursorcolumn = windows->colmax - strlen(buffer);
    SetCursorPosition(windows->rowmax, cursorcolumn);
    write(STDOUT_FILENO, buffer, strlen(buffer));
    switch (editor->type)
    {
    case COMMANDE:
        sprintf(buffer, "-- COMMAND --");
        break;
    case INSERTION:
        sprintf(buffer, "-- INSERTION --");
        break;
    case REMPLACEMENT:
        sprintf(buffer, "-- REPLACEMENT --");
        break;
    case NORMAL:
        sprintf(buffer, "-- NORMAL --");
    default:
        break;
    }
    cursorcolumn -= (strlen(buffer) + 1);
    SetCursorPosition(windows->rowmax, cursorcolumn);
    write(STDOUT_FILENO, buffer, strlen(buffer));
    SetCursorPosition(windows->row, windows->column);
    return 0;
}

//Refresh the screen 
int refresh_windows(Windows *windows, Editor *editor)
{
    char buffer[1024];
    sprintf(buffer, "\x1b[?25l");
    write(STDOUT_FILENO, buffer, strlen(buffer));
    int row;
    int maxrow = windows->rowmax;
    int decalage = 0;
    for (int i = 0; i < maxrow; i++)
    {
        int nbcolmax = 1;
        SetCursorPosition(i + decalage, 0);
        sprintf(buffer, "\x1b[K");
        write(STDOUT_FILENO, buffer, strlen(buffer));
        row = i + editor->row - editor->rowwin;
        if (i == maxrow - 1 && editor->size[row] > windows->colmax && windows->row == windows->rowmax)
        {
            editor->rowwin -= (editor->size[row] / windows->colmax);
            refresh_windows(windows, editor);
            return 0;
        }
        if (i == maxrow - 1 && editor->size[row] > windows->colmax && windows->row == -1)
        {

            editor->rowwin += (editor->size[row] / windows->colmax);
            refresh_windows(windows, editor);
            return 0;
        }

        for (int j = 0; j < editor->size[row]; j++)
        {

            if (j == (windows->colmax + 1) * nbcolmax)
            {
                write(STDOUT_FILENO, "\r\n", 2);
                write(STDOUT_FILENO, buffer, strlen(buffer));
                nbcolmax++;
                decalage++;
                maxrow--;
                SetCursorPosition(i + decalage, 0);
            }
            if (editor->grid[row][j] == '\n')
            {
                write(STDOUT_FILENO, buffer, strlen(buffer));
                write(STDOUT_FILENO, "\r\n", 2);
                write(STDOUT_FILENO, buffer, strlen(buffer));
            }
            else
            {
                write(STDOUT_FILENO, &editor->grid[row][j], 1);
            }
        }
        if (i == 0 && windows->row == -1)
        {
            editor->row = row;
        }
        if (i == maxrow - 1 && windows->row == windows->rowmax)
        {
            editor->row = row;
        }
    }
    if (windows->row == windows->rowmax)
    {
        editor->rowwin = windows->rowmax - decalage - 1;
        windows->row--;
        windows->row = windows->rowmax - (editor->size[editor->row] / windows->colmax) - 1;
    }
    else if (windows->row == -1)
    {
        editor->rowwin = 0;
        windows->row++;
    }
    info(editor, windows);
    SetCursorPosition(windows->row, windows->column);
    sprintf(buffer, "\x1b[?25h");
    write(STDOUT_FILENO, buffer, strlen(buffer));
    return 0;
}

//Set position depending on x and y
void SetCursorPosition(int x, int y)
{
    char buffer[1024];
    sprintf(buffer, "\x1b[%d;%dH", x + 1, y + 1);
    write(STDOUT_FILENO, buffer, strlen(buffer));
}

//function to use the 4 differents arrows
void arrow_keys(Editor *editor, Windows *windows, char *buffer)
{
    if (!strcmp(buffer, ARROW_RIGHT))
    {
        if ((editor->column < editor->size[editor->row] - 2) || (editor->size[editor->row + 1] == 0 && editor->column < editor->size[editor->row] - 1))
        {

            editor->column++;
            if (windows->column == windows->colmax - 1)
            {
                editor->column++;
                windows->column = 0;
                windows->row++;
            }
            else
                windows->column++;
        }
        else if (editor->type == INSERTION && editor->grid[editor->row][editor->column] != ' ' && editor->size[editor->row] != 1)
        {
            editor->grid[editor->row][editor->column + 1] = ' ';
            editor->grid[editor->row][editor->column + 2] = '\n';
            editor->column++;
            windows->column++;
            editor->size[editor->row]++;
        }
    }
    else if (!strcmp(buffer, ARROW_LEFT))
    {
        if (windows->column - 1 >= 0)
        {
            editor->column--;
            windows->column--;
        }
        else if (editor->rowwin != windows->row)
        {
            editor->column = windows->column = windows->colmax - 1;
            windows->row--;
        }
    }
    else if (!strcmp(buffer, ARROW_DOWN))
    {
        if (editor->row < GRIDROW)
        {
            if (windows->row <= windows->rowmax && editor->size[editor->row + 1] != 0)
            {
                int ligne = ((editor->size[editor->row]) / (windows->colmax + 1)) + 1;
                if (editor->size[editor->row] > editor->size[editor->row + 1] && editor->size[editor->row] / windows->colmax != editor->size[editor->row + 1] / windows->colmax)
                    ligne -= (editor->column / windows->colmax);
                editor->row++;
                if (windows->row + ligne != windows->rowmax)
                {
                    windows->row += ligne;
                    editor->rowwin++;
                }
                else
                {
                    windows->row++;
                }
                if (editor->size[editor->row - 1] > editor->size[editor->row] && editor->column > editor->size[editor->row] - 2)
                {
                    editor->column = editor->size[editor->row] - 2;
                    if (editor->column < 0 || editor->size[editor->row + 1] == 0)
                        editor->column++;
                    windows->column = editor->column;
                    if (editor->size[editor->row] > windows->colmax)
                        windows->column = editor->column - (ligne - 1) * windows->colmax;
                }
            }
        }
    }
    else if (!strcmp(buffer, ARROW_UP))
    {

        if (windows->row >= 0 && editor->row > 0)
        {
            int ligne = ((editor->size[editor->row - 1]) / (windows->colmax + 1)) + 1;
            if (editor->size[editor->row] > editor->size[editor->row - 1] && editor->size[editor->row] / windows->colmax != editor->size[editor->row - 1] / windows->colmax)
                ligne += (editor->column / windows->colmax);
            editor->row--;

            if (windows->row - ligne >= -1 && editor->rowwin != 0)
            {
                windows->row -= ligne;
                editor->rowwin--;
            }
            else
            {
                windows->row--;
            }
            if (editor->size[editor->row + 1] > editor->size[editor->row] && editor->column > editor->size[editor->row] - 2)
            {
                editor->column = editor->size[editor->row] - 2;
                if (editor->column < 0 || editor->size[editor->row + 1] == 0)
                    editor->column++;
                windows->column = editor->column;
                if (editor->size[editor->row] > windows->colmax)
                    windows->column = editor->column - (ligne - 1) * windows->colmax;
            }
        }
    }
}

//mode commande
int input_commande(Editor *editor, Windows *windows, char buffer)
{
    if (buffer == ESC)
    {
        editor->type = NORMAL;
        commande[0] = '\0';
    }
    if (commande[0] == ':' || buffer == ':')
    {

        char bufwrite[1024];
        char *token;
        SetCursorPosition(windows->rowmax, 0);
        sprintf(bufwrite, "\x1b[K");
        write(STDOUT_FILENO, bufwrite, strlen(bufwrite));
        if (buffer == '\r')
        {
            if (!strcmp(commande, ":q"))
            {
                return 0;
            }
            else if (!strcmp(commande, ":w"))
            {
                if (editor->name == NULL)
                {
                    write(STDOUT_FILENO, "You need to specify a name.... Press any key to continue", 57);
                }
                else
                    save(editor);
            }
            else if ((token = strtok(commande, " ")) != NULL)
            {
                if (!strcmp(token, ":w"))
                {
                    char *name = strtok(NULL, " ");
                    if (name != NULL)
                    {
                        editor->name = name;
                        save(editor);
                    }
                }
            }
            commande[0] = '\0';
            write(STDOUT_FILENO, bufwrite, strlen(bufwrite));
        }
        else if (buffer == BACKSPACE)
        {
            commande[strlen(commande) - 1] = '\0';
            sprintf(bufwrite, "%s", commande);
            write(STDOUT_FILENO, commande, strlen(commande));
        }
        else
        {
            commande[strlen(commande) + 1] = '\0';
            commande[strlen(commande)] = buffer;
            sprintf(bufwrite, "%s", commande);
            write(STDOUT_FILENO, commande, strlen(commande));
        }
    }
    return 1;
}

//mode normal
int input_normal(Editor *editor, Windows *windows, char buffer)
{
    if (buffer == ':')
    {
        SetCursorPosition(windows->rowmax, 0);
        write(STDOUT_FILENO, &buffer, 1);
        commande[0] = buffer;
        commande[1] = '\0';
        editor->type = COMMANDE;
    }
    else if (commande[0] == '/' || buffer == '/')
    {
        int nbligne = -1;
        char bufwrite[1024];
        SetCursorPosition(windows->rowmax, 0);
        sprintf(bufwrite, "\x1b[K");
        write(STDOUT_FILENO, bufwrite, strlen(bufwrite));
        if (buffer == '\r')
        {
            for (int i = editor->row; i < GRIDROW; i++)
            {
                for (int j = 0; j < editor->size[i]; j++)
                {
                    if (strstr(editor->grid[i], commande + 1) != NULL)
                    {
                        nbligne = i;
                    }
                }
                if (nbligne != -1)
                    break;
            }
            if (nbligne != -1)
            {
                for (int i = editor->column; i > 0; i--)
                {
                    arrow_keys(editor, windows, ARROW_LEFT);
                    refresh_windows(windows, editor);
                }
                for (int i = editor->row; i != nbligne; i++)
                {
                    arrow_keys(editor, windows, ARROW_DOWN);
                    refresh_windows(windows, editor);
                }
                char lookingfor[1024];
                int i = 0;
                for (int j = editor->column; j < editor->size[editor->row] - 1; j++)
                {
                    lookingfor[i] = editor->grid[editor->row][j];
                    lookingfor[i + 1] = '\0';
                    if (strstr(lookingfor, commande + 1) != NULL)
                    {

                        break;
                    }
                    i++;
                    arrow_keys(editor, windows, ARROW_RIGHT);
                    refresh_windows(windows, editor);
                }
            }
            commande[0] = '\0';
        }
        else if (buffer == BACKSPACE)
        {
            commande[strlen(commande) - 1] = '\0';
            sprintf(bufwrite, "%s", commande);
            write(STDOUT_FILENO, bufwrite, strlen(bufwrite));
        }
        else
        {
            commande[strlen(commande) + 1] = '\0';
            commande[strlen(commande)] = buffer;
            sprintf(bufwrite, "%s", commande);
            write(STDOUT_FILENO, bufwrite, strlen(bufwrite));
        }
    }
    else if (buffer == 'i')
    {
        editor->type = INSERTION;
    }
    else if (buffer == 'r')
    {
        editor->type = REMPLACEMENT;
    }
    return 1;
}

//mode remplacement
int input_remplacement(Editor *editor, Windows *windows, char buffer)
{
    if (buffer == ESC)
    {
        editor->type = NORMAL;
    }
    else if (buffer == '\r')
    {

         char **gridcopie = malloc(GRIDROW * sizeof(char *));
        int *sizecopie = malloc(GRIDROW * sizeof(int));
        for (int i = 0; i < GRIDROW; i++)
        {
            gridcopie[i] = malloc(GRIDCOL * sizeof(char));
            sizecopie[i] = editor->size[i];
            for (int j = 0; j < editor->size[i]; j++)
            {
                gridcopie[i][j] = editor->grid[i][j];
            }
        }
        for (int i = 1; i < GRIDROW; i++)
        {
            int row = editor->row + i;
            if (editor->size[row] == 0)
                break;
            for (int j = 0; j < sizecopie[row]; j++)
            {
                editor->grid[row + 1][j] = gridcopie[row][j];
            }
            editor->size[row + 1] = sizecopie[row];
        }
        for (int j = 0; j < editor->size[editor->row] - editor->column; j++)
        {
            editor->grid[editor->row + 1][j] = gridcopie[editor->row][j + editor->column];
        }
        editor->size[editor->row + 1] = editor->size[editor->row] - (editor->column);
        editor->size[editor->row] = editor->column + 1;
        editor->grid[editor->row][editor->column] = '\n';
        editor->rowwin += (editor->column / windows->colmax) + 1;
        for (int i = 0; i < GRIDROW; i++)
        {
            free(gridcopie[i]);
        }
        free(gridcopie);
        free(sizecopie);
        editor->column = windows->column = 0;
        if (windows->row == windows->rowmax - 1)
        {
            arrow_keys(editor, windows, ARROW_DOWN);
        }
        else
        {
            editor->row++;
            windows->row++;
        }
    }
    else if (buffer != BACKSPACE)
    {
        editor->grid[editor->row][editor->column] = buffer;
    }
    return 1;
}

//insertion mode
int input_insertion(Editor *editor, Windows *windows, char buffer)
{
    if (buffer == ESC)
    {
        editor->type = NORMAL;
    }
    else if (buffer == BACKSPACE && editor->row >= 0)
    {
        if (editor->column == 0)
        {
            if (editor->size[editor->row] == 1)
            {
                char **gridcopie = malloc(GRIDROW * sizeof(char *));
                int *sizecopie = malloc(GRIDROW * sizeof(int));
                for (int i = 0; i < GRIDROW; i++)
                {
                    gridcopie[i] = malloc(GRIDCOL * sizeof(char));
                    sizecopie[i] = editor->size[i];
                    for (int j = 0; j < editor->size[i]; j++)
                    {
                        gridcopie[i][j] = editor->grid[i][j];
                    }
                }
                for (int i = 1; i < GRIDROW; i++)
                {
                    int row = editor->row + i;
                    if (editor->size[row] == 0)
                        break;
                    for (int j = 0; j < sizecopie[row]; j++)
                    {
                        editor->grid[row - 1][j] = gridcopie[row][j];
                    }
                    editor->size[row - 1] = sizecopie[row];
                }
                for (int i = 0; i < GRIDROW; i++)
                {
                    free(gridcopie[i]);
                }
                free(gridcopie);
                free(sizecopie);
                editor->column = windows->column = 0;
                if (windows->row == 0)
                {
                    arrow_keys(editor, windows, ARROW_UP);
                }
            }
            else if (editor->row > 0)
            {
                for (int i = 0; i < editor->size[editor->row] - 1; i++)
                {
                    editor->grid[editor->row - 1][editor->size[editor->row - 1] - 1] = editor->grid[editor->row][i];
                    editor->size[editor->row - 1]++;
                }
                editor->size[editor->row] = 1;
                input_insertion(editor, windows, BACKSPACE);
            }
        }
        else if (editor->column != 0)
        {
            char *old = malloc(editor->size[editor->row] * sizeof(char));
            for (int i = 0; i < editor->size[editor->row]; i++)
            {
                old[i] = editor->grid[editor->row][i];
            }
            for (int i = editor->column - 1; i < editor->size[editor->row]; i++)
            {
                editor->grid[editor->row][i] = old[i + 1];
            }
            free(old);
            editor->column--;
            windows->column--;
            editor->size[editor->row]--;
            if (editor->grid[editor->row][editor->column] == ' ' && editor->size[editor->row] == 2)
            {
                editor->grid[editor->row][editor->column] = '\n';
                editor->size[editor->row]--;
            }
        }
    }
    else if (buffer == '\r')
    {
        char **gridcopie = malloc(GRIDROW * sizeof(char *));
        int *sizecopie = malloc(GRIDROW * sizeof(int));
        for (int i = 0; i < GRIDROW; i++)
        {
            gridcopie[i] = malloc(GRIDCOL * sizeof(char));
            sizecopie[i] = editor->size[i];
            for (int j = 0; j < editor->size[i]; j++)
            {
                gridcopie[i][j] = editor->grid[i][j];
            }
        }
        for (int i = 1; i < GRIDROW; i++)
        {
            int row = editor->row + i;
            if (editor->size[row] == 0)
                break;
            for (int j = 0; j < sizecopie[row]; j++)
            {
                editor->grid[row + 1][j] = gridcopie[row][j];
            }
            editor->size[row + 1] = sizecopie[row];
        }
        for (int j = 0; j < editor->size[editor->row] - editor->column; j++)
        {
            editor->grid[editor->row + 1][j] = gridcopie[editor->row][j + editor->column];
        }
        editor->size[editor->row + 1] = editor->size[editor->row] - (editor->column);
        editor->size[editor->row] = editor->column + 1;
        editor->grid[editor->row][editor->column] = '\n';
        editor->rowwin += (editor->column / windows->colmax) + 1;
        for (int i = 0; i < GRIDROW; i++)
        {
            free(gridcopie[i]);
        }
        free(gridcopie);
        free(sizecopie);
        editor->column = windows->column = 0;
        if (windows->row == windows->rowmax - 1)
        {
            arrow_keys(editor, windows, ARROW_DOWN);
        }
        else
        {
            editor->row++;
            windows->row++;
        }
    }
    else if (buffer != BACKSPACE)
    {
        char *old = malloc(editor->size[editor->row] * sizeof(char));
        for (int i = 0; i < editor->size[editor->row]; i++)
        {
            old[i] = editor->grid[editor->row][i];
        }
        for (int i = editor->column + 1; i < editor->size[editor->row] + 1; i++)
        {
            editor->grid[editor->row][i] = old[i - 1];
        }
        editor->grid[editor->row][editor->column] = buffer;
        free(old);
        editor->column++;
        if (editor->size[editor->row] - editor->column < windows->colmax && windows->column == windows->colmax - 1)
        {
            windows->row++;
            windows->column = 0;
        }
        else
            windows->column++;
        editor->size[editor->row]++;
    }

    return 1;
}

//mouse input 
int input_mouse(Windows *windows, Editor *editor, Mouse *mouse, char *buffer)
{
    int left = buffer[0] & 0x1;
    int x = buffer[1];
    int y = buffer[2];
    mouse->x += x;
    mouse->y += y;
    if (mouse->x > windows->colmax)
        mouse->x = windows->colmax;
    else if (mouse->x < 0)
        mouse->x = 0;
    if (mouse->y >= windows->rowmax)
        mouse->y = windows->rowmax - 2;
    else if (mouse->y < 0)
        mouse->y = 0;
    if (left == 1)
    {
        if (mouse->oldx > mouse->x)
        {
            for (int i = 0; i < (mouse->oldx - mouse->x); i++)
            {
                arrow_keys(editor, windows, ARROW_LEFT);
            }
        }
        else if (mouse->oldx < mouse->x)
        {
            for (int i = 0; i > (mouse->oldx - mouse->x); i--)
            {
                arrow_keys(editor, windows, ARROW_RIGHT);
            }
        }
        if (mouse->oldy > mouse->y)
        {
            for (int i = 0; i < (mouse->oldy - mouse->y); i++)
            {
                arrow_keys(editor, windows, ARROW_DOWN);
            }
        }
        else if (mouse->oldy < mouse->y)
        {
            for (int i = 0; i > (mouse->oldy - mouse->y); i--)
            {
                arrow_keys(editor, windows, ARROW_UP);
            }
        }
        mouse->oldx = mouse->x;
        mouse->oldy = mouse->y;
        return 0;
    }
    return 1;
}
