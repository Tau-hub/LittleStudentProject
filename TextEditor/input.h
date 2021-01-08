#ifndef _HEADER_INPUT_
#define _HEADER_INPUT_
#include "struct.h"
#include <string.h>
#include <stdlib.h>
#include <stdio.h>


//different codes for input keys

#define ESC 27
#define ARROW_UP "\x1b[A"
#define ARROW_DOWN "\x1b[B"
#define ARROW_RIGHT "\x1b[C"
#define ARROW_LEFT "\x1b[D"
#define DEL "\x1b[2"
#define BACKSPACE 127


char commande [64];


void SetCursorPosition(int x, int y);
void arrow_keys(Editor *editor, Windows *windows, char *buffer);
int input_commande(Editor *editor, Windows *windows, char buffer);
int input_insertion(Editor *editor, Windows *windows, char buffer);
int input_remplacement(Editor *editor, Windows *windows, char buffer);
int input_normal(Editor *editor, Windows *windows, char buffer);
int input_mouse(Windows *windows, Editor *editor, Mouse *mouse ,char *buffer);
int refresh_windows(Windows *windows, Editor *editor);


#endif