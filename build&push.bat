@ECHO OFF
docker build . -t omeka_s_cag
docker tag omeka_s_cag registry.docker.libis.be/omeka_s_cag
docker push registry.docker.libis.be/omeka_s_cag
ECHO Image built, tagged and pushed successfully
PAUSE
