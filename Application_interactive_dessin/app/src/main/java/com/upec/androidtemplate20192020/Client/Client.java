package com.upec.androidtemplate20192020.Client;

import android.util.Log;

import com.upec.androidtemplate20192020.UI.MainActivity;
import com.upec.androidtemplate20192020.UI.Utilities.Dessin;
import com.upec.androidtemplate20192020.UI.Utilities.Point;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintStream;
import java.lang.reflect.Array;
import java.net.Inet4Address;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.ArrayList;

public class Client extends Thread {

    private String ip = null;
    private String name;
    private PrintStream os;
    private BufferedReader in;
    private Socket s;
    private MainActivity activity;






    public Client(String ip, String name, MainActivity activity) {
        this.ip = ip;
        this.name = name;
        this.activity = activity;
    }

    public Client(String name, MainActivity activity) {
        this.name = name;
        this.activity = activity;
    }

    @Override
    public void run() {

        if (this.ip == null) {
            try {
                this.ip = Inet4Address.getLocalHost().getHostAddress();
            } catch (UnknownHostException e) {
                e.printStackTrace();
            }
        }
        try {
            Log.d("test", "ip " + ip + " name " + name);
            s = new Socket(ip, 1234);
            os = new PrintStream(s.getOutputStream(), true);
            in = new BufferedReader(
                    new InputStreamReader(s.getInputStream()));
            register();
            activity.clientConnected();
            doRun();
        } catch (IOException e) {
           this.close();
            e.printStackTrace();
            activity.clientNotConnected();
        }
    }


    public void changeName(String name) {
        this.name = name;
    }

    public void register() {
        os.println("NAME");
        os.println(name);
    }

    public void sendNewPoint(Point p) {
        os.println("POINT");

        Log.d("POINT", "start");
        os.println(p.getX()/Dessin.width);
        os.println(p.getY()/Dessin.height);
        Log.d("taille" , "x  : " + p.getX()/Dessin.width + " y : " + p.getY()/Dessin.height);
        os.println(p.getColor());
        os.println(p.getThickness());
        Log.d("POINT", "done");
    }


    private void doRun() throws IOException {
        String cmd;
        while (in != null) {
            if((cmd = in.readLine()) == null)
                throw new IOException();
            switch (cmd) {
                case "ULIST":
                    ArrayList<String> userList = new ArrayList<>();
                    while (!(cmd = in.readLine()).equals(".")) {
                        Log.d("Userlist", cmd);
                        userList.add(cmd);
                        MainActivity.userList = userList;
                    }
                    break;
                case "POINT":
                    try {
                        float x = Float.parseFloat(in.readLine());
                        float y = Float.parseFloat(in.readLine());
                        int color = Integer.parseInt(in.readLine());
                        int thickness = Integer.parseInt(in.readLine());
                        Point p = new Point((x*Dessin.width), (y*Dessin.height), color, thickness);
                        Log.d("taille_recu" , "x  : " + p.getX() + " y : " + p.getY());
                        MainActivity.points.add(p);
                        Log.d("POINTS", p.toString());
                        activity.notifyNewPoint();
                    } catch (IOException  | NumberFormatException e) {
                       e.printStackTrace();
                    }

                    break;
                case "RESETSHEET":
                    MainActivity.points = new ArrayList<>();
                    activity.notifyNewPoint();
                    break;
                case "DISCONNECT" :
                    this.close();
            }
        }
    }

    public void close() {
        MainActivity.userList = new ArrayList<>();
        try {
            if(s != null)
                s.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}

