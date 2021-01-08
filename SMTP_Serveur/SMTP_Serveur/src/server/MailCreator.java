package server;

import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.file.FileSystems;
import java.util.concurrent.Semaphore;
import java.util.logging.Level;
import java.util.logging.Logger;

import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.Multipart;
import javax.mail.Session;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeBodyPart;
import javax.mail.internet.MimeMessage;
import javax.mail.internet.MimeMultipart;

public class MailCreator extends Thread {
	private static int counter = 0;
	private String to;
	private String from;
	private String subject;
	private String body;
	private static Semaphore s = new Semaphore(1);
	
	public MailCreator(String to, String from, String subject, String body)
	{
		this.to = to;
		this.from = from;
		this.subject = subject;
		this.body = body;
		this.start();
	}

	@Override
	public void run()
	{
		this.createMessage();
	}
	public synchronized  void createMessage() {

		
		try {
			Message message = new MimeMessage(Session.getInstance(System.getProperties()));
			message.setFrom(new InternetAddress(from));
			message.setRecipients(Message.RecipientType.TO, InternetAddress.parse(to));
			message.setSubject(subject);
			body.replace("..", ".");
			MimeBodyPart content = new MimeBodyPart();
			content.setText(body);
			Multipart multipart = new MimeMultipart();
			multipart.addBodyPart(content);
			message.setContent(multipart);
			try {
				s.acquire();
				counter++;
				FileOutputStream email = new FileOutputStream( ServerMain.SAVE_PATH +  "/email" + counter + ".eml");
				message.writeTo(email);
				email.close();
				s.release();
			} catch (InterruptedException e) {
				e.printStackTrace();
			}
			
		} catch (MessagingException ex) {
			Logger.getLogger(MailCreator.class.getName()).log(Level.SEVERE, null, ex);
		} catch (IOException ex) {
			Logger.getLogger(MailCreator.class.getName()).log(Level.SEVERE, null, ex);
		}
	}
}


