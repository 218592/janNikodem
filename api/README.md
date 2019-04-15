# API

## Config (server-side only)

Set *$api_secret, $host, $user, $pass* variables.

## Usage

API expects proper secret and mode values as GET parameters, eg.:

```
http://host:8080/api.php?secret=api_secret&mode=insert-band
```

Where *host* and *api_secret* values match the ones in server config.

Incorrect GET parameters will return error and end execution.

All modes take POST parameters as variables.

### Modes

##### insert-band
Add new wristband to database.

| Parameter | Type    | Description |
|-----------|---------|-------------|
| band_id   | Integer | Band ID     |
| pulse     | Integer | Pulse       |

##### update-band
Update pulse of wristband with given ID.

| Parameter | Type    | Description |
|-----------|---------|-------------|
| band_id   | Integer | Band ID     |
| pulse     | Integer | Pulse       |

##### remove-band
Remove wristband with given ID from database.

| Parameter | Type    | Description |
|-----------|---------|-------------|
| band_id   | Integer | Band ID     |