CC=gcc
CFLAGS=-W -Werror -Wall  -g -std=gnu99
SOURCES = main.c struct.c input.c
OBJECTS = $(SOURCES:.c=.o)
EXEC=vimLite
.PHONY : default clean

default: $(EXEC)

struct.o : struct.c struct.h
input.o : input.c input.h struct.h
main.o :  struct.h input.h main.c 


%.o :%.c
	$(CC) -o $@ -c  $< $(CFLAGS)

$(EXEC) : $(OBJECTS)
			$(CC) -o $@ $^ 

clean : 
	 rm $(EXEC) $(OBJECTS)