LDFLAGS = -lX11
CC = gcc
CX = g++

all:
	$(CC) $(LDFLAGS) -o show_desktop show_desktop.c
	$(CX) $(LDFLAGS) -o find_window find_window.cpp
