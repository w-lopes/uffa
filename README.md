<p align="center">
   <img src="https://i.imgur.com/RRGpKfP.png"/>
</p>
<h1 align="center">Untitled Framework For API</h1>

## Start a new project

Remove `/custom` from `.gitignore` in a real project, everything will be created there! :)

Grant permission to execute main script:
```bash
chmod +x uffa
```

Need some help?
```bash
./uffa help
```

## Config file

Uffa uses `json` format to configure a project (Why not?).

To generate a new config file based on example just run the following command:
```bash
./uffa config:import
```
It can be used to merge new attributes from .example.json file to config.json.

The reverse way can also be done. Using the command below, the .example.json file is updated with the new attributes from local config.
```bash
./uffa config:export
```

## API Documentation
If you already created any `resource`, you can open and read its generated documentation using your http server (like apache) or run it on dev mode:
```bash
./uffa server # default is http://localhost:8080
```

Then just open http://localhost:8080 using ~~firefox~~ any browser :)
