use bevy::prelude::*;
use bevy_simple_text_input::TextInputPlugin;

mod splash;
mod game;
mod menu;

// fn main() {
//     App::new()
//         .add_plugins(DefaultPlugins)
//         .add_plugins(TextInputPlugin)
//         .add_systems(Startup, setup)
//         .run();
// }

// fn setup(mut commands: Commands) {
//     commands.spawn(Camera2d);
//     commands.spawn((
//         TextInput,
//         Node {
//             padding: UiRect::all(Val::Px(5.0)),
//             border: UiRect::all(Val::Px(2.0)),
//             ..default()
//         },
//         BorderColor::all(Color::BLACK)
//     ));
// }

// fn main() {
//     println!("Hello, world!");
// }

const TEXT_COLOR: Color = Color::srgb(0.9, 0.9, 0.9);

// Enum that will be used as a global state for the game
#[derive(Clone, Copy, Default, Eq, PartialEq, Debug, Hash, States)]
enum GameState {
    #[default]
    Splash,
    Menu,
    Game,
}

// One of the two settings that can be set through the menu. It will be a resource in the app
#[derive(Resource, Debug, Component, PartialEq, Eq, Clone, Copy)]
enum DisplayQuality {
    Low,
    Medium,
    High,
}

// One of the two settings that can be set through the menu. It will be a resource in the app
#[derive(Resource, Debug, Component, PartialEq, Eq, Clone, Copy)]
struct Volume(u32);

fn main() {
    App::new()
        .add_plugins(DefaultPlugins)
        .add_plugins(TextInputPlugin)
        // Insert as resource the initial value for the settings resources
        .insert_resource(DisplayQuality::Medium)
        .insert_resource(Volume(7))
        // Declare the game state, whose starting value is determined by the `Default` trait
        .init_state::<GameState>()
        .add_systems(Startup, setup)
        // Adds the plugins for each state
        .add_plugins((splash::splash_plugin, menu::menu_plugin, game::game_plugin))
        .run();
}

fn setup(mut commands: Commands) {
    commands.spawn(Camera2d);
}

