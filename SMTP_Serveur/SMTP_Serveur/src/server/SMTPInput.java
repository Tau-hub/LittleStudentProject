package server;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;

public class SMTPInput {
	private InputStream in;
	private SMTPProtocol handler;
	private ISMTPLogger logger;

	public SMTPInput(InputStream in, SMTPProtocol handler, ISMTPLogger logger) throws IOException {
		this.in = in;
		this.handler = handler;
		this.logger = logger;
	}

	public void doRun() throws IOException {
		try (BufferedReader is = new BufferedReader(new InputStreamReader(in))) {
			String from = null;
			ArrayList<String> receivers = new ArrayList<String>();
			String subject = null;
			String body = "";
			while (true) {
				String line;
				line = is.readLine();
				if (line == null)
					throw new IOException("null");
				String[] command = line.split(" ");
				logger.clientGotCommand(line);
				switch (command[0]) {
				case "EHLO":
					handler.sendAuth();
					break;
				case "HELO":
					handler.sendAuth();
					break;
				case "AUTH":
					switch (command[1]) {
					case "PLAIN":
						String code;
						if (command.length < 3) {
							handler.sendAskAuth();
							code = is.readLine();
							logger.clientGotCommand(code);
						} else
							code = command[2];
						if (handler.checkPlainAuth(code))
							handler.sendAuthenticationOk();
						else
							handler.sendAuthenticationFail();
						break;
					case "LOGIN":
						handler.sendAskAuthUsername();
						String username = is.readLine();
						logger.clientGotCommand(username);
						handler.sendAskAuthPassword();
						String password = is.readLine();
						logger.clientGotCommand(password);
						if (handler.checkAuthUsername(username) && handler.checkAuthPassword(password))
							handler.sendAuthenticationOk();
						else
							handler.sendAuthenticationFail();
						break;
					}

					break;
				case "MAIL":
					from = command[1].split(":")[1];
					handler.sendOk();
					break;
				case "RCPT":
					receivers.add(command[1].split(":")[1]);
					handler.sendOk();
					break;
				case "DATA":
					boolean body_open = false;
					handler.sendEndData();
					do {
						line = is.readLine();
						if (line == null)
							throw new IOException();
						logger.clientGotCommand(line);
						if (line.contains("Subject")) {
							subject = line.split(" ")[1];
						}
						if (body_open)
							body += line + "\n";
						if (line.isEmpty())
							body_open = true;
					} while (!line.equals("."));
					for (String receiver : receivers) {
						new MailCreator(receiver, from, subject, body.substring(0, body.length() - 2));
					}

					handler.sendOk();
					break;
				case "QUIT":
					handler.sendEndConnection();
					break;
				default:
					throw new IOException("Invalid input");
				}
			}
		}
	}

}
