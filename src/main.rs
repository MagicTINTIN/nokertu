use bevy::prelude::*;
use bevy_simple_text_input::{TextInput, TextInputPlugin};

fn main() {
    App::new()
        .add_plugins(DefaultPlugins)
        .add_plugins(TextInputPlugin)
        .add_systems(Startup, setup)
        .run();
}

fn setup(mut commands: Commands) {
    commands.spawn(Camera2d);
    commands.spawn((
        TextInput,
        Node {
            padding: UiRect::all(Val::Px(5.0)),
            border: UiRect::all(Val::Px(2.0)),
            ..default()
        },
        BorderColor::all(Color::BLACK)
    ));
}

// fn main() {
//     println!("Hello, world!");
// }
