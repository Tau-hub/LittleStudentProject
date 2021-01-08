package server;

import java.io.IOException;
import java.net.Socket;
import java.util.Base64;

public class HandleClient implements Runnable, SMTPProtocol {
	private Socket s;
	private ISMTPLogger logger;
	private SMTPOutput sos;
	private SMTPInput sis;
	private String username;
	private String password;

	public HandleClient(Socket s, ISMTPLogger logger) {
		this.s = s;
		this.logger = logger;
		username = "Username";
		password = "Password";
	}

	private boolean stop = false;

	public static String getB64(String name) {
		return new String(Base64.getEncoder().encode(name.getBytes()));
	}

	@Override
	public void run() {

		try {
			sos = new SMTPOutput(s.getOutputStream(), logger);
			sis = new SMTPInput(s.getInputStream(), this, logger);
			sendClientConnected();
			sis.doRun();

		} catch (IOException ex) {
			logger.systemMessage(ex.getMessage());
			if (!stop) {
				finish();
			}
		}
	}

	public synchronized void finish() {
		if (!stop) {
			stop = true;
			try {
				s.close();
			} catch (IOException ex) {
				ex.printStackTrace();
			}
			logger.clientDisconnected(s.toString());
		}
	}

	public void sendClientConnected() {
		sos.sendClientConnected(s.getInetAddress().toString().substring(1));
	}

	@Override
	public void sendOk() {
		sos.sendOk();
	}

	@Override
	public void sendEndData() {
		sos.sendEndData();
	}

	@Override
	public void sendEndConnection() {
		sos.sendEndConnection();
		this.finish();
	}

	@Override
	public void sendAuth() {
		sos.sendAuth();
	}

	@Override
	public void sendAskAuthUsername() {
		sos.sendAskAuthUsername();
	}

	@Override
	public void sendAskAuthPassword() {
		sos.sendAskAuthPassword();
	}

	@Override
	public void sendAuthenticationOk() {
		sos.sendAuthenticationOk();
	}

	@Override
	public void sendAuthenticationFail() {
		sos.sendAuthenticationFail();
	}

	@Override
	public boolean checkPlainAuth(String code) {
		return getB64('\0' + this.username + '\0' + this.password).equals(code);
	}

	public boolean checkAuthUsername(String username) {
		return getB64(this.username).equals(username);
	}

	public boolean checkAuthPassword(String password) {
		return getB64(this.password).equals(password);
	}

}
