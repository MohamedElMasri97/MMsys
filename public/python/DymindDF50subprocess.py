import subprocess
import sys

# sys.stdout = open("DymindDF50subprocess.txt", "w+")
DETACHED_PROCESS = 0x00000008
pid = subprocess.Popen([sys.executable] + [sys.argv[6]] + sys.argv[1:6], creationflags=DETACHED_PROCESS).pid

print(pid)
