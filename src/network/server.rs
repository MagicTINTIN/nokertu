use std::io::{Read, Write};
use std::net::TcpListener;
use std::thread::{self, sleep};
use std::time::Duration;

pub fn launch(port: u16) {
    let listener = TcpListener::bind(format!("127.0.0.1:{port}")).unwrap();
    println!("listening started, ready to accept");
    for stream in listener.incoming() {
        thread::spawn(move || {
            let mut stream = stream.unwrap();
            let mut count = 0u64;

            loop {
                stream
                    .write(format!("Hello number {count}\r\n").as_bytes())
                    .unwrap();
                let mut buf = [0; 128];
                let size = stream.read(&mut buf).unwrap();
                let s = String::from_utf8(buf[0..size].to_vec()).unwrap();
                println!("received: {s}");
                // scanf!("Hello number {count}\r\n");
                let value = s
                    .strip_prefix("Hi number ")
                    .unwrap()
                    .trim()
                    .parse::<u64>()
                    .unwrap();
                count = value + 1;
                sleep(Duration::from_secs(1));
            }
        });
    }
}
