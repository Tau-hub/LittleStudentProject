#ifndef _HEADER_STRUCT_
#define _HEADER_STRUCT_
#define _GNU_SOURCE
#include <unistd.h>
#include <fcntl.h>
#include <sys/ioctl.h>
#include <stdlib.h>

//Max col and max row
#define GRIDCOL 999
#define GRIDROW 9999


typedef enum Type
{
    COMMANDE,
    REMPLACEMENT,
    INSERTION,
    NORMAL
} Type;

typedef struct Mouse {
    int x;
    int y;
    int oldx;
    int oldy;
} Mouse;

typedef struct Editor
{
    int row;
    int column;
    char **grid;
    Type type;
    int *size;
    int rowwin;
    char *name;
} Editor;

typedef struct Windows
{
    int row;
    int column;
    int rowmax;
    int colmax;
} Windows;

Mouse new_mouse();
Editor new_editor(char *name);
Windows new_windows();

#endif