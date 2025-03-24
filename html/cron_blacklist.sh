5 * * * * docker exec -i postgresdb psql -U sae -d sae -c "select tripenarvor.unblacklist_rate();" >> /docker/sae/data/logs/crontab.log 2>&1
