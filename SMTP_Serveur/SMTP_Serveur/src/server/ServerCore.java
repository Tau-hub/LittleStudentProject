package server;

import java.io.IOException;
import java.net.ServerSocket;


import java.util.logging.Level;
import java.util.logging.Logger;
import java.lang.System;


public class ServerCore extends ServerCoreAbs  {
	public ServerCore(int port) throws IOException {
		super(port);
	}
	
	@Override
	public void run() {
		try (ServerSocket ss = new ServerSocket(port)) {
			this.run(ss);
		} catch (IOException e) {
			System.out.println("Could not bind port " + port);
			Logger.getLogger(ServerCore.class.getName()).log(Level.SEVERE,null, e);
		}
	}
	

}
