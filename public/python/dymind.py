import sys
import os
import requests
import threading
import time
from threading import Timer
import socket
py3 = True

sys.stdout = open("DymindDF50.txt", "a+")

class RepeatedTimer(object):
    def __init__(self, interval, function, *args, **kwargs):
        self._timer     = None
        self.interval   = interval
        self.function   = function
        self.args       = args
        self.kwargs     = kwargs
        self.is_running = False
        self.start()

    def _run(self):
        self.is_running = False
        self.start()
        self.function(*self.args, **self.kwargs)

    def start(self):
        if not self.is_running:
            self._timer = Timer(self.interval, self._run)
            self._timer.start()
            self.is_running = True

    def stop(self):
        self._timer.cancel()
        self.is_running = False


class looper():
    # initialization which inherets the threading functionality and the toploevel one instance
    def __init__(self, main_win):
        self.main_win = main_win

    def makeHeader(self, type,control_id):
        return b'MSH|' + b'^~\&' + 7 * b'|' + type + b'|'+control_id+b'|P|2.3.1||||||ASCII|||\r'

    def MSA(self,control_id):
        return b'MSA|AA|'+control_id+b'\r'

    def ERR(self, code=b'0'):
        return b'ERR|' + code + b'|\r'

    def QAK(self, state=b'OK'):
        return b'QAK|SR|' + state + b'|\r'

    def QRD(self):
        return self.data.split(b'\r')[1] + b'\r'

    def QRF(self):
        return self.data.split(b'\r')[2] + b'\r'

    #  tries to accept a client on the connection socket and if succeed then call handler
    def run(self):
        while True:
            self.main_win.show(
                '\n\nserver : accepting' + '\nserver: ' + str((self.main_win.ip, self.main_win.port)))
            try:
                self.cliant, self.cliantAddress = self.main_win.connection.accept()
            except:
                self.main_win.show('forced to close before connection')
                return
            self.main_win.show('\nserver: client detected' + str(self.cliantAddress))
            self.handler()

    # keeps the connection open with the client and close if the client wats to
    # and recieves messages and handle them accordingly
    def handler(self):
        try:
            while True:
                # after establishing connection the handler start waiting for TCP messages
                self.main_win.show('\nhandler: in waiting' + str(self.cliant))
                # tries to recieve a message with maximum of 8KByte
                self.data = self.cliant.recv(8192)
                self.main_win.show('\nhandler: ' + str(self.data))
                # if the message was '\n' then the client would like to close the TCP connection
                if not self.data:
                    self.main_win.show('\nhandler: disconnected')
                    self.cliant.close()
                    return
                x = self.data.split(b'/r')
                # print(x)
                # if OUL in DATA recieved then this message is a result message
                if b'ORU' in self.data:
                    self.oru()
                # if QRY in DATA then its clearly a query
                # elif b'QRY' in self.data:
                #     self.qry()
        except ConnectionResetError:
            self.main_win.show('\nhandler: ERROR, disconnected')
        except ConnectionAbortedError:
            self.main_win.show('\nhandler: ERROR, disconnected')
        finally:
            self.cliant.close()
            self.main_win.show('\nhandler: exiting handler')

    def accept(self,control_id,ack = b''):
        self.cliant.send(b'\x0b'+self.makeHeader(b'ACK^R01',control_id) + self.MSA(control_id) + b'\x1c\r')

    # accepts message and extract the barcode and the results from the ORU message
    def oru(self):
        segments = [segment.split(b'|') for segment in self.data.split(b'\r') if segment]
        control_id = segments[0][9]
        self.accept(ack=b'^R21',control_id = control_id)
        segments = [segment.split(b'|') for segment in self.data.split(b'\r') if segment]
        patient = {}
        id = segments[3][3].decode()
        if segments[10][5]:
            patient['WBC'] = segments[10][5].decode()
        if segments[11][5]:
            patient['lymPer'] = segments[11][5].decode()
        if segments[12][5]:
            patient['granPer'] = segments[12][5].decode()
        if segments[13][5]:
            patient['midPer'] = segments[13][5].decode()
        if segments[14][5]:
            patient['LYM'] = segments[14][5].decode()
        if segments[15][5]:
            patient['gran'] = segments[15][5].decode()
        if segments[16][5]:
            patient['mid'] = segments[16][5].decode()
        if segments[17][5]:
            patient['RBC'] = segments[17][5].decode()
        if segments[18][5]:
            patient['HGB'] = segments[18][5].decode()
        if segments[19][5]:
            patient['HCT'] = segments[19][5].decode()
        if segments[20][5]:
            patient['MCV'] = segments[20][5].decode()
        if segments[21][5]:
            patient['MCH'] = segments[21][5].decode()
        if segments[22][5]:
            patient['MCHC'] = segments[22][5].decode()
        if segments[23][5]:
            patient['RDW-CV'] = segments[23][5].decode()
        if segments[24][5]:
            patient['RDW-SD'] = segments[24][5].decode()
        if segments[25][5]:
            patient['PLT'] = segments[25][5].decode()
        if segments[26][5]:
            patient['MPV'] = segments[26][5].decode()
        if segments[27][5]:
            patient['PDW'] = segments[27][5].decode()
        if segments[28][5]:
            patient['PCT'] = segments[28][5].decode()
        if segments[29][5]:
            patient['P-LCR'] = segments[29][5].decode()
        if segments[30][5]:
            patient['P-LCC'] = segments[30][5].decode()
        test = {'result': patient, 'id': id}
        self.main_win.show(str(test))
        self.main_win.writer(test)

class Instrument():
    device_name = 'AIA2000'
    localcode2globalcode = {}
    globalcode2localcode = {}
    requestIimeLimit = 4
    # creats a variable that holdt the inverse of the localcode2globalcode
    # called in init
    def globalCode2localCode(self):
        self.globalcode2localcode = {}
        for i in self.localcode2globalcode:
            self.globalcode2localcode[self.localcode2globalcode[i]] = i

    # connection parameters
    ip = 0
    port = '5122'

    # api parameters
    sampleid = b'sample_code'
    apigetter = 'http://165.22.27.138/lims/v1/sample/tests'

    daemon = True

    # gets the right ip
    def getIP(self):
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        s.connect(('8.8.8.8', 80))
        self.ip = s.getsockname()[0]
        s.close()

    # gets the connection ip and port for the local network instrument

    # establish a socket connection to the indicated ip and port number for the instrument
    def get_connection(self):
        s = socket.socket()
        try:
            print('almost binding')
            s.bind((self.ip, int(self.port)))
            print('binded')
            self.show('get-connection: connection has been created')
            s.listen(1)
            return s
        except:
            s.close()
            self.err('ERR','get-connection: there is problem while creating connection')
            return None

    # disables connect button
    # shows connecting message
    # gets the connection instanse of of get_connection function
    # if connection is valid then create a looper instance and start the loop
    def run(self):
        print('running')
        if self.checkstatus() == 'on':
            print('run: run is going and status is on')
            self.show('run: connecting')
            self.connection = self.get_connection()
            if self.connection:
                print('run: almost starting')
                self.looper.run()
                print('run: started')
            else:
                self.err('ERR','run: there is no connection')

    # closes the looper instance and the connection sucket
    # enable the connect button and disable disconnect button
    def disconnect(self,status='off',message = 'disconnected'):
        print('disconnect:')
        if self.connection:
            print('connection is there')
            try:
                self.looper.cliant.close()
                del self.looper
            except AttributeError:
                print('disconnect: no connection created already')
                pass
            self.connection.close()
            if self.status != 'nc':
                self.show('disconnect:' + message)
        self.updatestatus(status)
        sys.exit()

    # contact the LIMS api through a given url and gets the sample data
    # using barcode
    def getSampleParameters(self,sampleBarCode ):
        print('getSampleParameters')
        resp = requests.get(self.apigetter, params=self.sampleid+b'='+sampleBarCode.encode(),timeout=self.requestIimeLimit)
        if resp.status_code == 200:
            respjson = resp.json()
            required_tests = []
            if 'error' in respjson:
                return False
            for i in respjson[0]['parameters']:
                required_tests.append(i['code'])
            return required_tests
        else:
            return False

    # inserts test that correspond to a given barcode to the database
    def testset(self, result):
        print('testset')
        obj = {'sample': result,'id':self.id}
        print(obj)
        x = requests.post(self.url + '/ResultSet', data=obj,timeout=self.requestIimeLimit)
        if x.status_code !=200:
            print(x.content.decode())
            print('invalid status')
            self.err('nc')

    # upload the last test result and
    # try to upload unuploaded tests
    def writer(self,result):
        print('writer')
        if self.checkstatus() == 'on':
            self.testset(result)

    # turns off the connect button and start the run function
    # this function only works if the connection button is active
    def start1(self):
        print('starting')
        self.postPID()
        self.show('starting')
        self.run()

    # show used to print strings on the connection state scrolled text box
    def show(self,string):
        if self.status != 'nc':
            print('showing: ' + string)
            myobj = {
                'message':string,
                'id':self.id
            }
            try:
                x = requests.post(self.url+'/show', data=myobj,timeout=self.requestIimeLimit)
                if x.status_code !=200:
                    print('error in showing unvalid status')
                    self.err('nc')
                else:
                    print(x.json())
                    self.status = x.json()['status']
                    if self.status !='on':
                        self.disconnect()
            except:
                self.err('nc')
        else:
            self.disconnect()


    def checkstatus(self):
        if self.status == 'on':
            try:
                resp = requests.get(self.url+'/status/' + str(self.id),timeout=self.requestIimeLimit)
                if resp.status_code == 200:
                    respjson = resp.json()
                    self.status = respjson['status']
                    if self.status !='on':
                        self.disconnect('OFF','Disconnected')
                    return respjson['status']
                else:
                    self.err('nc')
                    return None
            except:
                self.err('nc')
                return None
        else:
            self.disconnect()

    def updatestatus(self, string):
        if self.status != 'nc':
            self.status = string
            if self.status != 'nc':
                obj = {'status': string, 'id': str(self.id)}
                requests.post(self.url + '/status', data=obj,timeout=self.requestIimeLimit)

    def postPID(self):
        pid = os.getpid()
        obj = {'id': self.id, 'pid': pid}
        # try:
        print(self.url + '/pid')
        print(obj)
        resp = requests.post(self.url+'/pid',data=obj,timeout=self.requestIimeLimit)
        print(resp.content)
        if resp.status_code == 200:
            return True
        else:
            self.err('nc')
            return None
        # except:
        #     print('exception in PID')
        #     self.err('nc')
        #     return None


    def err(self, err='ERR', message='Error occurred'):
        print('err: ' + err)
        self.disconnect(err, message)

    # the initialization function
    def __init__(self,ip='auto',port='5600',url='http://localhost:8000/api',id=1,apigetter='http://165.22.27.138/lims/v1/sample/tests'):
        if ip == 'auto':
            self.getIP()
        else:
            self.ip = ip
        self.id = id
        self.port = port
        self.url = url
        self.getIP()
        self.globalCode2localCode()
        self.status = 'on'
        self.looper = looper(self)
        self.connection = None
        self.apigetter = apigetter

if __name__=='__main__':
    # try:
    print('main args')
    for arg in sys.argv:
        print(arg)
    main = Instrument(sys.argv[1],sys.argv[2],sys.argv[3],sys.argv[4],sys.argv[5])
    main.start1()
    # except:
    #     main = Instrument()
    #     main.start1()



