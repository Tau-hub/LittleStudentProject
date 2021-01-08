package com.upec.androidtemplate20192020.Server;

import android.util.Log;

import com.upec.androidtemplate20192020.UI.MainActivity;
import com.upec.androidtemplate20192020.UI.Utilities.Dessin;
import com.upec.androidtemplate20192020.UI.Utilities.Point;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintStream;
import java.net.Socket;
import java.util.concurrent.CopyOnWriteArrayList;

import static com.upec.androidtemplate20192020.UI.MainActivity.points;

public class HandleClient implements Runnable {

    private Socket s;
    private PrintStream os;
    private BufferedReader in;

    private enum ClientState {
        ST_INIT, ST_NORMAL
    }

    private ClientState state = ClientState.ST_INIT;


    public HandleClient(Socket s) throws IOException {
        this.s = s;
        os = new PrintStream(s.getOutputStream(), true);
        in = new BufferedReader(
                new InputStreamReader(s.getInputStream()));

    }


    @Override
    public void run() {
        try {
            doRun();
        } catch (IOException e) {
            finish();
            e.printStackTrace();
        }


    }

    public void disconnection()
    {
       os.println("DISCONNECT");
    }

    private void finish()
    {
        try {
            s.close();
        } catch(IOException e)
        {
            e.printStackTrace();
        }
        finally {
            ClientEvent.removeClient(this);
        }

    }
    private void doRun() throws IOException {
        String msg;
        while ((msg = in.readLine()) != null) {
            switch (msg) {
                case "NAME":
                    msg = in.readLine();
                    registerClient(msg);
                    break;
                case "POINT":
                    try {
                        float x = Float.parseFloat(in.readLine());
                        float y = Float.parseFloat(in.readLine());
                        int color = Integer.parseInt(in.readLine());
                        int thickness = Integer.parseInt(in.readLine());
                        ClientEvent.notifyNewPoint(new Point(x,y,color,thickness),this);
                    }
                    catch (IOException  | NumberFormatException e) {
                        e.printStackTrace();
                    }
                    break;

            }
        }
        finish();
    }

    private void registerClient(String name) {
        if (state == ClientState.ST_INIT) {
            ClientEvent.addClient(name, this);
            state = ClientState.ST_NORMAL;
        } else {
            ClientEvent.renameClient(name, this);
        }
    }

    public void resetSheet()
    {
        os.println("RESETSHEET");
    }
    public void userListchanged() {
        os.println("ULIST");
        Log.d("ULIST", "ULIST");
        for (String client : ClientEvent.clientList.values()) {
            os.println(client);
            Log.d("ULIST", client);
        }
        os.println(".");
        Log.d("ULIST", ".");
    }

    public void updatePoint(Point p) {

        os.println("POINT");
        Log.d("POINT", "POINT to : " + ClientEvent.clientList.get(this));
        os.println(p.getX());
        os.println(p.getY());
        os.println(p.getColor());
        os.println(p.getThickness());
        Log.d("POINT", "done");

    }

    public void sendAllPoints() {
            Log.d("ALLPOINTS", "start");
            CopyOnWriteArrayList<Point> points_copy = new CopyOnWriteArrayList<>(points);
            for(Point p : points_copy)
            {

                os.println("POINT");
                os.println(p.getX()/ Dessin.width);
                os.println(p.getY()/Dessin.height);
                os.println(p.getColor());
                os.println(p.getThickness());
                Log.d("taille_send" , "x  : " + p.getX()/Dessin.width + " y : " + p.getY()/Dessin.height);
            }
        Log.d("ALLPOINTS", "done");
    }
}
