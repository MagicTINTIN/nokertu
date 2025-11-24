use std::io::{Read, Write};
use std::net::TcpStream;
use std::thread::sleep;
use std::time::Duration;

pub fn launch(address: &str) -> std::io::Result<()> {
    println!("connecting to server {address}");
    let mut stream = TcpStream::connect(address).unwrap();

    // stream.write(&[1])?;
    // stream.read(&mut [0; 128])?;
    //
    let mut count;

    loop {
        let mut buf = [0; 128];
        let size = stream.read(&mut buf).unwrap();
        let s = String::from_utf8(buf[0..size].to_vec()).unwrap();
        println!("received: {s}");
        let value = s
            .strip_prefix("Hello number ")
            .unwrap()
            .trim()
            .parse::<u64>()
            .unwrap();
        count = value + 1;
        sleep(Duration::from_secs(1));
        stream
            .write(format!("Hi number {count}\r\n").as_bytes())
            .unwrap();
    }
    // Ok(())
}