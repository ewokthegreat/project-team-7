import csv

#Constructor for WordBank Class. A class that accesses a csv file and creates a list from it.
class WordBank:
    def __init__(self, wordBankList,file):
        self.wordBankList = wordBankList
        self.file = file

        self.setUpWordBank()

    def setUpWordBank(self):
        f = open(self.file)
        csv_f = csv.reader(f)

        self.wordBankList = []
        for row in csv_f:
            self.wordBankList.append(row)

        print (self.wordBankList)

wordBank = WordBank([],'../doc/SpiderWordBank.csv')