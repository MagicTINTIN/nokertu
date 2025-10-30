# TODO / Rules

## 
- [ ] create public/private lobbies
- [ ] Fill lobby with AI

## Card rules
### Classic cards
- [ ] numbered cards (1 - 10 | spades, clubs, diamonds, hearts)
- [ ] Jack (next doesn't play), Queen (change rotation), King (next takes 2 cards)
### Tarot cards
🃏 are joker cards (that can be placed on the stack at any moment, the color by default is the one on which it is placed)\
🪄 are effect cards, once used they don't go on the stack

- [ ] 🃏 The Fool           | Le Mat             : copies the card on which it is placed
- [ ] 🪄 The Magician       | Le Bateleur        : exchanges a card with the slots under the table (can't be used when there is 1 or less stack card in the deck) (if no card was under the table it takes one from the draw)
- [ ] 🃏 The High Priestess | La Papesse         : defines the new color
- [ ] 🃏 The Empress        | L'Impératrice      : changes rotation and add (can be cumulated) a +2
- [ ] 🃏 The Emperor        | L'Empereur         : +4 cards and define the new color
- [ ] 🪄 The Hierophant     | Le Pape            : resets your "+*" counter 
- [ ] 🪄 The Lovers         | L'Amoureux         : merge shufflle and redistribute cards between them
- [ ] 🪄 The Chariot        | Le Chariot         : lets you give 1 of your cards to the next player
- [ ] 🃏 Justice            | La Justice         : sends the effect of last special card played to its sender
- [ ] 🃏 The Hermit         | L'Ermite           : divide by two the number of "+N" card you have to draw
- [ ] 🃏 Wheel of Fortune   | La Roue de Fortune : is transformed as a random stack card | mulitplies by [0.5-2] | next draw [1..10] cards
- [ ] 🪄 Strength           | La Force           : during the game your kings get an additional "+1"
- [ ] 🪄 The Hanged Man     | Le Pendu           : removes 2 cards from your deck
- [ ] 🃏 Death              | La Mort            : +2 to every player (including you?)
- [ ] 🪄 Temperance         | Tempérance         : at the end of the game, divide your score by 2
- [ ] 🃏 The Devil          | Le Diable          : multiplies the number of cards of the next player by 2
- [ ] 🪄 The Tower          | La Maison Dieu     : when you have the same card lets you place one more identical card at the same time
- [ ] 🃏 The Stars          | L'Étoile           : shuffle players positions (?) (swap position with someone)
- [ ] 🪄 The Moon           | La Lune            : will randomly remove either all spades or all clubs from the deck
- [ ] 🪄 The Sun            | Le Soleil          : will randomly remove either all diamonds or all heart from the deck
- [ ] 🃏 Judgement          | Le Jugement        : gives the number of "+*" counter of the next player
- [ ] 🃏 The World          | Le Monde           : rolls your deck to a fresh new deck (with at max N cards?)

### Global effects/events
- [ ] Eclipse: when The Moon and The Sun are played in the same round, all "+N" base value are doubled (limited duration?)
- [ ] The Empire: when The Emperor and The Empress are played in the same round, every player receive a King (+2) in their deck
- [ ] Heaven: when The High Priestess and The Hierophant are played in the same round, everyone can place any card for one round (regardless of the color)
- [ ] Parkinson: when a player has 2 strengths in the same game, then for 2 rounds, all players can place up to 2 cards

Global effects in shop
-> bid wars
effects:
  - **Eclipse**
  - **Parkinson** for the whole game
  - **Magic** (5% chance when draw to draw an ephemeral magic card (e.g. the magician))
  - **Kalm** divide malus effects by 2
  - **Effect** cards are marked according to their type
  - **ef** (rare effect) every card is transparent

## Karma

Stats count in points and maybe effects
- number of last cards played
- average card number in deck during the game
- number of tarot cards placed
- "+*" counter: (1×"+2" + 2×"+4")×(1+"×2") placed

## Final score

The lowest is your score, the better is your place\
value of remaining cards * avg number of cards (need to be in the HUD)  + "+*" counter

separated draws

# R-mode

wheel of fortune used = 1 free common item in the shop?

## Items to buy
- [ ] under the table slots
- [ ] Planned love
- [ ] cards
- [ ] paper cards (rip)
- [ ] stickers
- [ ] pencil (+1)