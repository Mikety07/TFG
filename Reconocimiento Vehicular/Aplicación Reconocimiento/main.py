import sys
import cv2
import easyocr
import requests  # Asegúrate de instalar esta librería con pip install requests
from PyQt5.QtWidgets import QApplication, QMainWindow, QPushButton, QVBoxLayout, QWidget, QLabel
from PyQt5.QtCore import QTimer, QSize
from PyQt5.QtGui import QImage, QPixmap
from collections import Counter  # Para contar las matrículas

# Modelo de OCR
reader = easyocr.Reader(['en'])

class MainWindow(QMainWindow):
    def __init__(self):
        super().__init__()
        self.setWindowTitle("Reconocimiento de Matrícula")
        self.setGeometry(100, 100, 800, 600)
        self.setup_ui()
        self.capturas = []  # Lista para almacenar las matrículas detectadas

    def setup_ui(self):
        layout = QVBoxLayout()

        # Botón verde redondo que diga "Pulse Aquí"
        self.capture_button = QPushButton("Pulse Aquí")
        self.capture_button.setStyleSheet("""
            QPushButton {
                background-color: green;
                color: white;
                border-radius: 50px;
                font-size: 20px;
            }
            QPushButton:hover {
                background-color: lightgreen;
            }
        """)
        self.capture_button.setFixedSize(QSize(150, 150))
        self.capture_button.clicked.connect(self.capture_and_predict)
        layout.addWidget(self.capture_button)

        # Área para mostrar el video de la webcam
        self.video_label = QLabel()
        layout.addWidget(self.video_label)

        # Label para mostrar la matrícula detectada
        self.resultado_label = QLabel()
        self.resultado_label.setStyleSheet("font-size: 24px; color: blue;")
        layout.addWidget(self.resultado_label)

        # Configuración de la cámara
        self.cap = cv2.VideoCapture(0)
        if not self.cap.isOpened():
            print("Error: No se pudo abrir la cámara.")

        self.timer = QTimer(self)
        self.timer.timeout.connect(self.display_video_stream)
        self.timer.start(30)

        # Widget central
        container = QWidget()
        container.setLayout(layout)
        self.setCentralWidget(container)

    def display_video_stream(self):
        ret, frame = self.cap.read()
        if ret:
            self.display_image(frame)
        else:
            print("Error al capturar el video")

    def display_image(self, img):
        qformat = QImage.Format_RGB888
        img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        out_image = QImage(img, img.shape[1], img.shape[0], img.strides[0], qformat)
        self.video_label.setPixmap(QPixmap.fromImage(out_image))
        self.video_label.setScaledContents(True)

    def capture_and_predict(self):
        self.capturas.clear()  # Limpiar las capturas anteriores
        num_capturas = 10  # Número de capturas deseadas
        for _ in range(num_capturas):
            ret, frame = self.cap.read()
            if ret:
                # OCR para leer la matrícula
                ocr_results = reader.readtext(frame)
                plate_text = " ".join([result[1] for result in ocr_results]).strip()
                if plate_text:
                    self.capturas.append(plate_text)  # Almacenar la matrícula detectada
                    print(f"Matrícula detectada: {plate_text}")
        
        # Determinar la matrícula más común
        if self.capturas:
            contador = Counter(self.capturas)
            matricula_mas_comun, repeticiones = contador.most_common(1)[0]  # Obtener la matrícula más común
            self.resultado_label.setText(f"Matrícula detectada: {matricula_mas_comun} ")

            # Si la matrícula se ha detectado al menos 3 veces, guardarla en la base de datos
            if repeticiones >= 3:
                self.save_plate_to_db(matricula_mas_comun)
        else:
            self.resultado_label.setText("Matrícula no detectada")

    def save_plate_to_db(self, plate_text):
        url = "http://localhost/GestionParking/PAGINAS/guardar_matricula.php"  # Cambia la URL según sea necesario
        data = {'matricula': plate_text}

        try:
            response = requests.post(url, data=data)
            if response.status_code == 200:
                print("Matrícula guardada en la base de datos.")
            else:
                print("Error al guardar la matrícula en la base de datos.")
        except Exception as e:
            print(f"Error de conexión: {e}")

if __name__ == "__main__":
    app = QApplication(sys.argv)
    window = MainWindow()
    window.show()
    sys.exit(app.exec_())
