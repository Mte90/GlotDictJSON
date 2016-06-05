#!/usr/bin/python

import json
import gspread
import os.path
from oauth2client.client import SignedJwtAssertionCredentials

if not os.path.isfile('credentials.json'):
    print "Credentials file missing, follow the instructions in the readme!"
    exit

json_key = json.load(open('credentials.json'))
scope = ['https://spreadsheets.google.com/feeds']

credentials = SignedJwtAssertionCredentials(json_key['client_email'], json_key['private_key'].encode(), scope)
gc = gspread.authorize(credentials)

doc = gc.open_by_key('1eleJcaX5ZOxAlaDiFMHk_uETenEQI8-1Rg99Jpll3mc')
worksheet = doc.get_worksheet(0)

#First column contain the english terms
english = worksheet.col_values(1)
english.pop(0)
english.pop(1)
english.pop(2)
english.pop(3)
#Italian translations
italian = worksheet.col_values(5)
italian.pop(0)
italian.pop(1)
italian.pop(2)
italian.pop(3)
#Italian comment column
italian_comment = worksheet.col_values(8)
italian_comment.pop(0)
italian_comment.pop(1)
italian_comment.pop(2)
italian_comment.pop(3)
glotdict = {}

#Convert that columns in json specific for GlotDict
i = 0
for engword in english:
    i = i + 1
    if engword == '':
        break
    if italian_comment[i - 1]=='' and italian[i - 1]=='':
        continue
    glotdict[engword] = { "pos": '', "translation": italian[i - 1], "comment": italian_comment[i - 1] }
#Save that information
with open('./dictionaries/it_IT.json', 'w') as outfile:
    json.dump(glotdict, outfile, indent=4, sort_keys=True)

print str(i) + " Glossary terms"
