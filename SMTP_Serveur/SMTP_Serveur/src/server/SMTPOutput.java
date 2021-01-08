package server;

import java.io.IOException;
import java.io.OutputStream;
import java.io.PrintWriter;

public class SMTPOutput implements SMTPProtocol {
	private PrintWriter os;
	private ISMTPLogger logger;
	
	public SMTPOutput(OutputStream out, ISMTPLogger logger) throws IOException {	
		this.os = new PrintWriter(out, true);
		this.logger = logger;
	}
	
	
	@Override
	public void sendClientConnected(String ip) {
		String greeting = "220 " + ip;
		os.println(greeting);
		logger.systemSendCommand(greeting);
	}
	
	@Override
	public void sendOk()
	{
		String ok = "250 OK";
		os.println(ok);
		logger.systemSendCommand(ok);
	}
	
	@Override
	public void sendEndData()
	{
		String endData = "354 End data with <CR><LF>.<CR><LF>";
		os.println(endData);
		logger.systemSendCommand(endData);
	}
	
	@Override 
	public void sendEndConnection()
	{
		String endConnection = "221 Bye";
		os.println(endConnection);
		logger.systemSendCommand(endConnection);
	}


	@Override
	public void sendAuth() {
			String auth = "250 AUTH PLAIN LOGIN";
			os.println(auth);
			logger.systemSendCommand(auth);
	}

	@Override
	public void sendAskAuth() {
		String askAuth = "334";
		os.println(askAuth);
		logger.systemSendCommand(askAuth);
	}
	
	@Override
	public  void sendAskAuthUsername() {
		String askAuth = "334 " + HandleClient.getB64("Username:");
		os.println(askAuth);
		logger.systemSendCommand(askAuth);
	}
	
	@Override
	public  void sendAskAuthPassword() {
		String askAuth = "334 " + HandleClient.getB64("Password:");
		os.println(askAuth);
		logger.systemSendCommand(askAuth);
		
	}

	public void sendAuthenticationOk() {
		String authOk = "235 Authentication OK";
		os.println(authOk);
		logger.systemSendCommand(authOk);
	}


	public void sendAuthenticationFail() {
		String authFail = "535 Authentication fail";
		os.println(authFail);
		logger.systemSendCommand(authFail);
		
	}
}
