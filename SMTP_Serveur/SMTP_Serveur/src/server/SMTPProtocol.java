package server;


public interface SMTPProtocol {
	public default void sendClientConnected(String msg) {}
	public default void sendOk() {}
	public default void sendEndData() {}
	public void sendEndConnection();
	public void sendAuth();
	public default void sendAskAuth() {}
	public default void sendAskAuthUsername() {}
	public default void sendAskAuthPassword() {}
	public default boolean checkPlainAuth(String code) { return false;}
	public default boolean checkAuthUsername(String username) { return false;}
	public default boolean checkAuthPassword(String password){ return false;}
	public default void sendAuthenticationOk() {	}
	public default void sendAuthenticationFail() {}
	
	
}
