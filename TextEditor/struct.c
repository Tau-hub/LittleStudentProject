#include "struct.h"

Mouse new_mouse()
{
    Mouse mouse;
    mouse.x = mouse.y = mouse.oldy = mouse.oldx = 0;
    return mouse;
}

//initialize an editor and stock the name of the parameter if there is one
Editor new_editor(char *name)
{
    Editor editor;
    editor.row = 0;
    editor.column = 0;
    editor.rowwin = 0;
    editor.type = NORMAL;
    if (name != NULL)
        editor.name = name;
    else
        editor.name = NULL;
    editor.grid = malloc(GRIDROW * sizeof(char *));
    for (int i = 0; i < GRIDROW; i++)
    {
        editor.grid[i] = malloc(GRIDCOL * sizeof(char));
    }
    for (int i = 0; i < GRIDROW; i++)
    {
        for (int j = 0; j < GRIDCOL; j++)
        {
            editor.grid[i][j] = 0;
        }
    }
    editor.size = malloc(GRIDROW * sizeof(int));
    for (int i = 0; i < GRIDROW; i++)
    {
        editor.size[i] = 0;
    }
    return editor;
}

Windows new_windows()
{

    Windows windows;
    struct winsize w;
    ioctl(0, TIOCGWINSZ, &w);
    windows.colmax = w.ws_col;
    windows.rowmax = w.ws_row - 1;
    windows.column = 0;
    windows.row = 0;
    return windows;
}
