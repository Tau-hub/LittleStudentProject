package server;

import java.io.File;
import java.io.IOException;
import java.nio.file.FileSystems;

public class ServerMain {
	public static String SAVE_PATH = FileSystems.getDefault().getPath("").toAbsolutePath().toString();
	public static String CERTIFICATE_PATH = null;
	public static String CERTIFICATE_PASSWORD = null;

	public static void main(String[] args) {
		boolean SSL = false;
		int port = 587;
		try {
			for (int i = 0; i < args.length; i++) {
				if (args[i].equals("-ssl")) {
					SSL = true;
					if (port == 587)
						port = 465;
				} else if (args[i].equals("-p")) {
					if (i + 1 < args.length) {
						try {
							port = Integer.parseInt(args[i + 1]);
						} catch (Exception e) {
							System.out.println("Port not possible, exiting...");
							System.exit(-1);
						}
						i++;

					}
				} else if (args[i].equals("-save_path")) {
					if (i + 1 < args.length) {
						if (new File(args[i + 1]).exists()) {
							SAVE_PATH = args[i + 1];
						} else {
							System.out.println("Path doesn't exist, default path : " + SAVE_PATH);
						}
						i++;
					}

				} else if (args[i].equals("-cert")) {
					if (i + 2 < args.length) {
						CERTIFICATE_PATH = args[i + 1];
						CERTIFICATE_PASSWORD = args[i + 2];
						i = i + 2;
					} else {
						System.out.println(
								"You need to have at least 2 args after -cert for  PATH and PASSWORD, exiting....");
						System.exit(-1);
					}

				}

			}
			if (SSL) {
				if (CERTIFICATE_PATH == null || CERTIFICATE_PASSWORD == null) {
					System.out.println("You can't run a ssl server without certs, exiting....");
					System.exit(-1);
				}
				new ServerCoreSSL(port);
			}

			else
				new ServerCore(port);
		} catch (IOException e) {
			System.out.println("Error during initialisation:" + e.toString());
		}
	}
}
