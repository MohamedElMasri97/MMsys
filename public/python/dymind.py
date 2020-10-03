import sys
import os
import requests
import threading
import time
from threading import Timer
import socket
py3 = True

sys.stdout = open("test.txt", "w+")

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

    # initialization which inherits the threading functionality and the instrument instance
    def __init__(self, main_win):
        self.main_win = main_win

    def makeHeader(self, type):
        return b'MSH|' + b'^~\&' + 7 * b'|' + type + b'|1|P|2.3.1||||||ASCII|||\r'

    def MSA(self):
        return b'MSA|AA|1|Message accepted|||0|\r'

    def ERR(self, code=b'0'):
        return b'ERR|' + code + b'|\r'

    def QAK(self,state = b'OK'):
        return b'QAK|SR|' + state + b'|\r'

    def QRD(self):
        return self.data.split(b'\r')[1] + b'\r'

    def QRF(self):
        return self.data.split(b'\r')[2] + b'\r'

    #  tries to accept a client on the connection socket and if succeed then call handler
    def run(self):
        print('looper-run: started')
        while True:
            if self.main_win.checkstatus() != 'on':
                print('looper-run: going out due status')
                return
            self.main_win.show(
                'looper-run-server : accepting ' + 'server: ' + str((self.main_win.ip, self.main_win.port)))
            try:
                print('loopser-run: waiting for client')
                self.cliant, self.cliantAddress = self.main_win.connection.accept()
            except:
                self.main_win.show('looper-run-server: forced to close before connection')
                return
            print('looper-run: heading to handler')
            self.main_win.show('looper-run-server: client detected' + str(self.cliantAddress))
            self.handler()

    # keeps the connection open with the client and close if the client wats to
    # and recieves messages and handle them accordingly
    def handler(self):
        self.data = b''
        try:
            while True:
                self.main_win.show('handler: in waiting' + str(self.cliant))
                self.data = self.cliant.recv(8192)
                self.main_win.checkstatus()
                if not self.data:
                    self.main_win.show('handler: disconnected due instrument request')
                    self.cliant.close()
                    return
                self.main_win.show('handler: ' + str(self.data))
                if b'ORU' in self.data:
                    self.oru()
        except ConnectionResetError:
            self.main_win.err('ERR','handler: ERROR, disconnected, ConnectionResetError')
            return
        except ConnectionAbortedError as e:
            self.main_win.err('ERR','handler: ERROR, disconnected, ConnectionAbortedError')
            return

    # accepts message and extract the barcode and the results from the ORU message
    def oru(self):
        self.accept(b'^R01')
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

        test = {}
        test['result'] = str(patient)
        test['id'] = id
        for i in test:
            print(i + ':' + test[i])
        self.main_win.show(str(test))
        self.main_win.writer(test)

    # send an acception response
    def accept(self,ack = b''):
        self.cliant.send(b'\x0b'+self.makeHeader(b'ACK^R01') + self.MSA() + self.ERR() + self.QAK() + b'\x1c\r')
        self.main_win.show('handler: accepted... ' + str(self.makeHeader(b'ACK') + b'\rMSA|AA|2|||||\r'))

    # extract barcode
    # gets the data by get sample parameters functioin
    # finally call the reply function
    # def qry(self):
    #     patient_barCode = self.data.split(b'\r')[1].split(b'|')[8]
    #     if patient_barCode:
    #         patient = self.grap_patient(patient_barCode.decode())
    #         self.reply(patient)
    #     else:
    #         self.reply('')
    #
    # # gets a sample required test using the get sample parameters function
    # def grap_patient(self,barcode):
    #     patient = {}
    #     patient['barcode'] = barcode.encode()
    #     patient['tests'] = [self.main_win.globalcode2localcode[i['code']].encode() for i in self.main_win.getSampleParameters(barcode)]
    #     return patient
    #     # patient info must be returned encoded.
    #
    # def reply(self, patient):
    #     if patient:
    #         massage = b'\x0b'+self.makeHeader(b'QCK^Q02') + self.MSA() + self.ERR() + self.QAK() + b'\x1c\r'
    #         self.cliant.send(massage)
    #         massage = b'\x0b' + self.makeHeader(b'DSR^Q03') + self.MSA() + self.ERR()+ self.QAK() + self.QRD() + self.QRF()
    #         dictionary = {
    #             4:  b'', 5: b'', 21: patient['barCode'], 26: b''
    #             , 6: b'', 24: b'N'
    #             }
    #         for i in range(1,29):
    #             if i in dictionary:
    #                 massage += b'DSP|' + str(i).encode() + b'||' + dictionary[i] + b'|||\r'
    #             else:
    #                 massage += b'DSP|' + str(i).encode() + b'|||||\r'
    #         for i in range(len(patient['tests'])):
    #             massage += b'DSP|' + str(i+29).encode() + b'||' + patient['tests'][i] + b'^^^|||\r'
    #         massage += b'DSC||\r\x1c\r'
    #         print(massage)
    #         self.cliant.send(massage)
    #     else:
    #         massage = b'\x0b' + self.makeHeader(b'DSR^Q02') + self.MSA() + self.ERR() + self.QAK(b'NF') + b'\x1c\r'
    #         self.cliant.send(massage)

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
        x = requests.post(self.url + '/resultset', data=obj,timeout=self.requestIimeLimit)
        if x.status_code !=200:
            print('uvalid status')
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
        try:
            print(self.url + '/pid')
            print(obj)
            resp = requests.post(self.url+'/pid',data=obj,timeout=self.requestIimeLimit)
            print(resp.content)
            if resp.status_code == 200:
                return True
            else:
                self.err('nc')
                return None
        except:
            time.sleep(10.0)
            try:
                print(self.url + '/pid')
                print(obj)
                resp = requests.post(self.url + '/pid', data=obj, timeout=self.requestIimeLimit)
                print(resp.content)
                if resp.status_code == 200:
                    return True
                else:
                    self.err('nc')
                    return None
            except:
                self.err('nc')
                return None


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
    try:
        print('main args')
        for arg in sys.argv:
            print(arg)
        main = Instrument(sys.argv[1],sys.argv[2],sys.argv[3],sys.argv[4],sys.argv[5])
        main.start1()
    except:
        main = Instrument()
        main.start1()
    # inst = Instrument()
    # inst = []

