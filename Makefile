LDFLAGS = -lX11
CC = clang
CX = clang++

all:
	$(CC) $(LDFLAGS) -o show_desktop show_desktop.c
	$(CX) $(LDFLAGS) -o find_window find_window.cpp
