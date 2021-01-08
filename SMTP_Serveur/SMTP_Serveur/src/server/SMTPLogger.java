package server;

public class SMTPLogger  implements ISMTPLogger{

	@Override
	public void clientConnected(String ip) {
		System.out.println("Client connected : " + ip);
	}

	@Override
	public void clientDisconnected(String ip) {
		System.out.println("Client disconnected : " + ip);
		
	}

	@Override
	public void clientGotCommand(String msg) {
		System.out.println("Client : " + msg );
		
	}

	@Override
	public void systemMessage(String msg) {
		System.out.println("Server message : " + msg);
		
	}

	@Override
	public void systemSendCommand(String msg) {
		System.out.println("Server : " + msg);	
	}

}
