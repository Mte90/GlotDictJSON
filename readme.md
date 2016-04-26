##What
That simple script convert the glossaries on glotpress automatically for GlotDict. Enjoy!

##Why python
The [Italian Glossary](https://docs.google.com/spreadsheets/d/1eleJcaX5ZOxAlaDiFMHk_uETenEQI8-1Rg99Jpll3mc/edit#gid=0) is on Google Drive so require specific workaround.  


##Install

```
pip install wheel
pip install oauth2client==1.5.2
pip install gspread
```

##Execute

```
./download.php
```

##Generate the OAuth key for python
Follow the instructions on [http://gspread.readthedocs.org/en/latest/oauth2.html](http://gspread.readthedocs.org/en/latest/oauth2.html), rename the file in credentials.json and put in the folder.   
Finally add the client_email of the file in the online document.

# Contributors

* [Daniele Scasciafratte](https://github.com/Mte90) - The developer
* Pascal Casier - For the php script
* Emre Erkan - For the new php script that use the csv