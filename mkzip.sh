#!/bin/bash
cd ../../
zip -r Metrof_FBConnect_magento-1.0.4.zip Metrof/FBConnect/ -x "Metrof/FBConnect/.git*" -x "*~" -x "Metrof/FBConnect/mkzip.sh"
