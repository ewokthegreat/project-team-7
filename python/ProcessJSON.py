import json
# import _mysql

obj_json = u'{"answer":[42], "abs":42}'

# try:
#     mysql_connection = _mysql.connect(user='',passwd='',
#                                       host='',db='')
# except mysql.connector.Error as err:
#     print (err)

class WordBank:

    def setUpWordBank(self, wordBankList=[], file=''):
        f = open(file)
        csv_f = csv.reader(f)

        wordBankList = []
        for row in csv_f:
            wordBankList.append(row)

        print (wordBankList)




class FBTextPost(object):
    def __init__(self, JSONObject):
        self.__dict__ = json.loads(obj_json)

fbTest = FBTextPost(obj_json)

WordBank.setUpWordBank([],'../doc/SpiderWordBank.csv')






print (fbTest.answer)
print (fbTest.abs)