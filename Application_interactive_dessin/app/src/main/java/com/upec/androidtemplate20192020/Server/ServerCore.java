package com.upec.androidtemplate20192020.Server;

import android.os.AsyncTask;
import android.util.Log;

import com.upec.androidtemplate20192020.Client.Client;
import com.upec.androidtemplate20192020.UI.MainActivity;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;

public class ServerCore extends Thread {
    private ServerSocket ss;

    @Override
    public void run() {

        try {
            ss = new ServerSocket(1234);
            while (true) {
                Socket s = ss.accept();
                new Thread(new HandleClient(s)).start();
                Log.d("Connected", s.getInetAddress().toString());
            }
        }
        catch(IOException e)
        {
            e.printStackTrace();
        }
     }

     public void close()
     {

         new Thread(new Runnable() {
             @Override
             public void run() {
                 ClientEvent.finishAll();
             }
         }).start();
         try {
             ss.close();
         } catch (IOException e) {
             e.printStackTrace();
         }
     }
    }

