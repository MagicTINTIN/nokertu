mod network;
use network::*;

fn main() {
    println!("Hello, world!");
    let args: Vec<String> = std::env::args().collect();
    if args.contains(&"--server".to_string()) {
        server::launch(9876);
    } else {
        let _ = client::launch("127.0.0.1:9876");
    }
}
