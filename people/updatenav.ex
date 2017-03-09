#!/bin/csh

for fl in *.php; do
mv $fl $fl.old
sed 's/FINDSTRING/REPLACESTRING/g' $fl.old > $fl
#rm -f $fl.old
done
