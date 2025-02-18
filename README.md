AzHAzizov/thunderbite-test-2025
When i click on an already selected tile

    protected function startsAtInTimeZone(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->starts_at?->setTimezone($this->timezone),
        );
    }

    protected function endsAtInTimeZone(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ends_at?->setTimezone($this->timezone),
        );
    }

- Incase you don't know its called reveledTiles
- Added a custom implementation to fix this (https://github.com/massivebrains/backend-test-2025/pull/6)
# Thunderbite Backend Test

This test is designed to evaluate your Laravel and algorithm knowledge, providing a good representation of the core skills required to join the Thunderbite team.

## Installation

The following instructions assume you are using Laravel Valet. If not, some steps may differ:

1. Clone this repository.
2. Run `composer install`.
3. Create and fill the `.env` file (an example is included as `/.env-example`).
4. Run `php artisan key:generate`.
5. Run `php artisan migrate` to create database tables.
6. Seed the database by running `php artisan db:seed`.
7. Run `npm install`.
8. Run `npm run dev`.
9. Visit `https://thunderbite-backend-test.test/backstage` and start coding!

Login credentials for the back office:
- Email: `test@thunderbite.com`
- Password: `test123`

---

## Test Tasks

The database seeder creates:
- Two test campaigns.
- 10,000 game records.
- 18 prize records.

Access the first test campaign here:

`https://thunderbite-backend-test.test/test-campaign-1?a=account&segment=low`

Here, you will find a board of 25 squares (5x5 grid):

|     |     |     |     |     |
| --- | --- | --- | --- | --- |
|     |     |     |     |     |
|     |     |     |     |     |
|     |     |     |     |     |
|     |     |     |     |     |

---

### Task 1: Create a Game

When an account accesses the campaign link (`?a=account`), create a new game record or retrieve an unfinished game for the account.

**Game Rules:**
- Clicking on the board reveals a random tile (prize) from the back office. The tile is selected based on the prize weighting configured in the back office.
- When a player collects three matching tiles (prizes), the game ends, and they win that prize.
- Display a message if the campaign has not started or has ended.

**Frontend Integration:**
- The game frontend expects a `$config` variable as a JSON string:
    ```php
    [
         'apiPath' => '/api/flip',     // API endpoint for tile click
         'gameId' => 'gameID',         // Passed as a POST parameter
         'revealedTiles' => [[
            'index' => 0,
            'image' => '/assets/tile.jpg'
        ]],                            // Restore clicked tiles for an ongoing game
        'message' => 'Campaign has ended' // Popup message when the game is unavailable
    ]
    ```

**API Interaction:**
- The frontend will send a `POST` request to `apiPath` with:
    ```json
    {
        "gameId": 0,
        "tileIndex": 0
    }
    ```
- The API should respond with:
    ```json
    {
        "tileImage": "/assets/tile.jpg"
    }
    ```
- The `tileImage` must correspond to an image uploaded via the back office for the associated prize. Ensure that the uploaded images are stored in an accessible location and that the correct path is returned based on the prize configuration and game logic.

- When the last tile (3rd match) is clicked, include the prize description in the response:
    ```json
    {
        "tileImage": "/assets/tile.jpg",
        "message": "You won a prize!"
    }
    ```

**Additional Requirements:**
1. Allow prize tile images to be uploaded via the back office.
    - Use uploaded images for the game tiles.
2. Use prize `weighting` for tile selection:
    ```php
    ->orderByRaw('-LOG(1.0 - RAND()) / weight')
    ```
    - **Bonus:** Create an illusion of equal chance for prizes until the final match.
3. Enforce prize `daily volume` limits based on a new back office input. This means that if a prize has been won the number of times defined by its daily limit in the back office, it cannot be won again on the same day. The game logic should prevent further attempts to award this prize until the daily limit is reset or adjusted in the back office. Ensure this restriction is reflected both in the tile selection logic and the overall game functionality.
4. Ensure that the game state can be resumed if the player refreshes the page or returns later.
5. Limit prize availability based on the player's `segment`, which is passed as a `GET` parameter. The database is seeded with example segments: `low`, `med`, and `high`. Players can only draw prizes assigned to the segment specified in the query parameter. For instance, if a player accesses the game with the segment set to `low` (`?segment=low`), they can only draw prizes designated for the `low` segment. Ensure this segmentation logic is consistently applied during prize selection and tile drawing.

---

### Task 2: Export Button in Back Office

Add an **Export** button to the back office **Games** section. This button should generate a CSV of all **filtered** games.

**Requirements:**
- Add table filters for easy data querying. Filters should align with those defined in the `Games::filter()` method.
- Improve database queries and loading times for this section as needed.

---

### Bonus Task: Testing

Cover the game logic with automated tests.
- **Preferred Framework:** `pest`.
- You may use `phpunit` if you prefer.

---

### Notes and Guidelines

1. **Database Updates:** Use new migrations for all changes.
2. **Optimization:** You are encouraged to optimize database structure, queries, and any other aspects of the project.
3. **Questions:** If you have any questions, please contact us via email.

---

## Evaluation Criteria

We will evaluate your submission based on:
1. Code clarity and maintainability.
2. Adherence to best practices in Laravel development.
3. Creativity in problem-solving and optimization.
4. Functionality and accuracy of implemented features.
5. Quality and coverage of automated tests.

Good luck, and we look forward to seeing your submission!
