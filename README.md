# Thunderbite Backend Test

This test is designed to test your laravel and algorithm knowledge, and it is a good representation of your core skills that are required to become a Thunderbite team member.

## Installation

For these instructions we assume you make use of Laravel Valet.
If you are not making use of Laravel Valet the below could differ a bit.

1. Clone this repository
2. Run composer install
3. Create and fill the .env file (example included /.env-example)
4. Run `php artisan migrate` to create database tables
5. Seed the database by running `php artisan db:seed`
6. Run `npm install`
7. Run `npm run dev`
8. Visit http://thunderbite-backend-test.test/backstage and happy coding

Login details: test@thunderbite.com / test123


## Test tasks

Database seed will create two test campaigns, 10k game records and 18 prize records.
You can enter first test campaign here:

http://thunderbite-backend-test.test/test-campaign-1?a=account&segment=low

Here you will find a board of 25 squares 5x5.

|  |  |  |  |  |
| --- | --- | --- | --- | --- |
|  |  |  |  |  |
|  |  |  |  |  |   
|  |  |  |  |  |
|  |  |  |  |  |

##### Task 1

###### Create a game.
When account enters the campaign link, a game record should be created or retrieved unfinished one for that account. Account should be passed as a query parameter.
Game objective, is to click on the board, each click will open random tile (prizes section in back office), when you collect 3 same tiles (prizes) on the board game is complete, and you win that prize.

Please also take in consideration campaign start and end dates, show campaign ended popup if it has ended (game can't be played anymore).

We have already created frontend controller and route for this task, you can find it here:
`FrontendController@loadCampaign`

There is simple frontend provided for this game, you will have to pass `$config` variable to the frontend, that has to be `json` string containing these parameters:


```php
[
     'apiPath' => '/api/openTile', // FE will make request to this endpoint when account clicks on the board square
     'gameId' => 'gameID' // this will be passed to `apiPath` endpoint as POST parameter
     'reveledTiles' => [[
        'index' => 0
        'image' => '/assets/tile.jpg'
    ]], // this will have to be used to restore what account has opened, if he/she refreshes the page
    'message' => 'Campaign has ended' // message popup layout on load, game can't be played with it 
]
```

FE will make this request with example body`POST apiPath`
```json
{
    "gameId": 0,
    "tileIndex": 0
}
```

FE will be expecting this response from the endpoint and will display image provided on clicked square:
```json
{
    "tileImage": "/assets/tile.jpg"
}
```

When last tile is being opened (3rd match) please include prize description in response, that will be shown in popup of the game:
```json
{
    "tileImage": "/assets/tile.jpg",
    "message": "You won a prize!"
}
```


* Please allow to upload prize tile image in back office, and use that image in the game. Each prize should have tile uploaded for it to work.
    * you can find example tile assets in `/resources/assets/`
* When drawing a tile please take in consideration prize `weighting` which is configurable in back office
    * You should use this to apply the weights:
  ```php
   ->orderByRaw('-LOG(1.0 - RAND()) / weight')
  ```
    * Bonus points if you could create an illusion that each prize has equal chance of being drawn until very end
    * Please note that prizes are segmented, default account segment should be `low`, account's segment should be passed loading campaign through GET parameter `segment`. Segments work like this, if account A comes with segment set low, only prizes assigned segment low could be drawn for this account.
    * Add extra input in backoffice `prizes` section crud, call it `daily volume`. Make sure this works in game logic, it shouldn't allow to draw more than `daily volume` of the same prize in one day.
    * Make sure that board can be resumed, if same account refreshes the page, or comes back to the game later.
    * `users` table is for back office access only, we don't need to create accounts in it, games table `account` field is enough.

##### Task 2
Add Export button to Games section, which will generate a CSV of all <u>filtered</u> games.

All accounts games can be accessed from backstage/games section.
This page should contain table filters, for easy data querying.
Filters that should be added can be seen in Games::filter() method (this particular query can be improved).

Database might have mistakes or bad practices, and needs optimization and/or extra fields in certain tables. Feel free to change anything you like.

Loading of the games section can be greatly improved with appropriate optimizations.

##### Bonus Task

Cover game logic with some tests. We prefer `pest` framework, but you can use standard `phpunit` if you prefer.

**All changes to the database should be done with new migrations.**

If you have any questions about the task, do not hesitate to ask.
