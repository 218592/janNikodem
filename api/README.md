# API

## Config (server-side only)

Set *$api_secret, $host, $user, $pass* variables.

## Usage

API expects JSON with proper secret and mode values on input. Example curl query:

```
curl -H "Content-Type: application/json" -X POST -d '{"secret":"api_secret","mode":"enable-band","band_id":"2"}' http://host:8080/api.php
```

Where *host* and *api_secret* values match the ones from server config.

Incorrect parameters will return error and end execution.

## Modes

#### Output

All modes return the following JSON:

##### status
Returned on execution finish.

| Parameter | Type    | Values      |
|-----------|---------|-------------|
| status    | String  | `success` or `error`|

If *status* value is `success`, optional parameter *data* might be appended to JSON:

| Parameter | Type    | Values      |
|-----------|---------|-------------|
| status    | String  | `success`   |
| *data*    | Any     |             |

If *status* value is `error`, parameter *error* is appended to JSON:

| Parameter | Type    | Values      |
|-----------|---------|-------------|
| status    | String  | `success`   |
| error     | String  |             |

#### Input

##### enable-band
Sets wristband as broadcasting.

| Parameter | Type    | Description |
|-----------|---------|-------------|
| band_id   | Integer | Band ID     |

Returns *id* in *data* parameter.

##### update-band
Updates pulse field of wristband.

| Parameter | Type    | Description |
|-----------|---------|-------------|
| id        | Integer | ID          |
| pulse     | Integer | Pulse       |

##### disable-band
Sets wristband as not broadcasting.

| Parameter | Type    | Description |
|-----------|---------|-------------|
| id        | Integer | ID          |

##### verify-login
Checks if requested *resc_id* exists in database.

| Parameter | Type    | Description |
|-----------|---------|-------------|
| resc_id   | Integer | Rescuer ID  |

##### activate-band
Sets wristband as active.

| Parameter | Type    | Description |
|-----------|---------|-------------|
| band_id   | Integer | Band ID     |
| resc_id   | Integer | Rescuer ID  |
| lat       | Any     | Latitude    |
| long      | Any     | Longitude   |
| color     | String  | Color       |