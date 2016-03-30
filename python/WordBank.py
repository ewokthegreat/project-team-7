import csv

# Constructor for WordBank Class. A class that accesses a csv file and creates a list from it.
class WordBank:

    def setUpWordBank(self, wordBankList=[], file=''):
        f = open(file)
        csv_f = csv.reader(f)

        wordBankList = []
        for row in csv_f:
            wordBankList.append(row)

        print (wordBankList)

#
# wordBank = WordBank()
# wordBank.setUpWordBank([],'../doc/SpiderWordBank.csv')