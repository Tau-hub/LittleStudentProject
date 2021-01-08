package com.upec.androidtemplate20192020.Server;

import android.util.Log;

import com.upec.androidtemplate20192020.UI.Utilities.Point;

import java.util.HashMap;

public class ClientEvent {
     static  HashMap<HandleClient, String> clientList = new HashMap<>();

      static synchronized  void addClient(String name, HandleClient client)
    {
        clientList.put(client, name);
        notifyUserListChanged();
        client.sendAllPoints();
    }

     static synchronized boolean renameClient(String newname, HandleClient client) {
        clientList.remove(client);
        clientList.put(client, newname);
        notifyUserListChanged();
        return true;
    }

    private static void notifyUserListChanged()
    {
        for(HandleClient client : clientList.keySet())
        {
           client.userListchanged();
        }

    }

     static void notifyNewPoint(Point p, HandleClient from)
    {
        for(HandleClient client : clientList.keySet())
        {
            Log.d("test", clientList.get(from) + "");
            if(from != client)
           client.updatePoint(p);
        }
    }

     static synchronized  void finishAll()
    {
        for(HandleClient client : clientList.keySet())
        {
            client.disconnection();
        }
        clientList = new HashMap<>();
    }


     static synchronized void removeClient(HandleClient client)
    {
        clientList.remove(client);
        notifyUserListChanged();
    }

    public  static synchronized void resetAllSheets()
    {
        for(HandleClient client : clientList.keySet())
            client.resetSheet();
    }
}
