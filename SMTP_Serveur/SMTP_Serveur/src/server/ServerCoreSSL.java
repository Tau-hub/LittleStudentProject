package server;

import java.io.IOException;
import java.net.ServerSocket;
import java.util.logging.Level;
import java.util.logging.Logger;

import javax.net.ssl.SSLServerSocketFactory;

public class ServerCoreSSL extends ServerCoreAbs {
	

	public ServerCoreSSL(int port) throws IOException {
		super(port);
	    System.setProperty("javax.net.ssl.keyStore", ServerMain.CERTIFICATE_PATH);
		System.setProperty("javax.net.ssl.keyStorePassword", ServerMain.CERTIFICATE_PASSWORD);
	}

	@Override
	public void run() {
		try (ServerSocket ss = SSLServerSocketFactory.getDefault().createServerSocket(port)) {
			this.run(ss);
		} catch (IOException e) {
			System.out.println("Could not bind port " + port);
			Logger.getLogger(ServerCore.class.getName()).log(Level.SEVERE, null, e);
		}
	}

}
