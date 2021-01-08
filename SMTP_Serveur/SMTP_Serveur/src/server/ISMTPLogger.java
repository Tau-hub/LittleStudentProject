package server;

public interface ISMTPLogger {
	public void clientConnected(String ip);
	public void clientDisconnected(String ip);
	public void clientGotCommand(String name);
	public void systemMessage(String msg);
	public void systemSendCommand(String msg);
}
