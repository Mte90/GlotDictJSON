##Why
The [Italian Glossary](https://docs.google.com/spreadsheets/d/1eleJcaX5ZOxAlaDiFMHk_uETenEQI8-1Rg99Jpll3mc/edit#gid=0) for the WordPress translators is on Google Drive and convert that for [GlotDict](https://github.com/Mte90/GlotDict) is a manually procedure.  
That simple script convert automatically for GlotDict. Enjoy!

##Install

```
pip install wheel
pip install oauth2client==1.5.2
pip install gspread
```

##Execute

```./convert.py```


##Generate the OAuth key
Follow the instructions on [http://gspread.readthedocs.org/en/latest/oauth2.html](http://gspread.readthedocs.org/en/latest/oauth2.html), rename the file in credentials.json and put in the folder.   
Finally add the client_email of the file in the online document.
