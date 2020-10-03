import threading
import sys
import dymind

class dymindth(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)

    def run(self):
        print('running')
        try:
            print('trying')
            main = dymind.Instrument(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4], sys.argv[5])
        except:
            print('except default')
            main = dymind.Instrument()
            print('starting')
            main.start1()


if __name__ == '__main__':
    x = dymindth()
    print('starting')
    x.start()
