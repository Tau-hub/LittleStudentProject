package server;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.net.SocketTimeoutException;


public class ServerCoreAbs extends Thread {
	protected int port;
	protected ISMTPLogger logger = null;

	public ServerCoreAbs(int port) throws IOException {
		this.port = port;
		logger = new SMTPLogger();
		logger.systemMessage("Server has started on port " + port + "....");
		this.start();
	}

	public void run(ServerSocket ss) throws IOException {
		ss.setSoTimeout(1000);
		while (true) {
			try {
				Socket s = ss.accept();
				logger.clientConnected(s.toString());
				new Thread(new HandleClient(s, logger)).start();
			} catch (SocketTimeoutException ex) {
			}
		}
	}
}
