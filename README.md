# CompraTEC


## Remove all containers

```bash
    docker stop $(docker ps -aq)
    docker rm $(docker ps -aq)
    docker volume rm $(docker volume ls --filter dangling=true -q)
```

## Instructions
