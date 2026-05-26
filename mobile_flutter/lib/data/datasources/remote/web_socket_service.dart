import 'package:socket_io_client/socket_io_client.dart' as IO;
import '../../../core/constants/api_constants.dart';
import '../../../core/utils/logger.dart';
import '../../../core/utils/secure_storage.dart';
import '../../../core/constants/storage_keys.dart';

class WebSocketService {
  static WebSocketService? _instance;
  late IO.Socket _socket;
  bool _isConnected = false;

  WebSocketService._internal();

  static WebSocketService getInstance() {
    if (_instance == null) {
      _instance = WebSocketService._internal();
    }
    return _instance!;
  }

  Future<void> connect() async {
    final token = await SecureStorage.read(StorageKeys.accessToken);
    
    _socket = IO.io(ApiConstants.baseUrl, {
      'transports': ['websocket'],
      'autoConnect': false,
      'query': {'token': token},
    });

    _socket.onConnect(_onConnect);
    _socket.onDisconnect(_onDisconnect);
    _socket.onConnectError(_onConnectError);
    _socket.onError(_onError);
    
    _socket.connect();
  }

  void disconnect() {
    if (_isConnected) {
      _socket.disconnect();
      _socket.dispose();
      _isConnected = false;
    }
  }

  void _onConnect() {
    _isConnected = true;
    Logger.info('WebSocket connected');
  }

  void _onDisconnect(dynamic data) {
    _isConnected = false;
    Logger.info('WebSocket disconnected');
  }

  void _onConnectError(dynamic error) {
    Logger.error('WebSocket connection error', error);
  }

  void _onError(dynamic error) {
    Logger.error('WebSocket error', error);
  }

  void subscribeToAlerts(Function(dynamic) callback) {
    _socket.on('alert', callback);
  }

  void subscribeToIncidents(Function(dynamic) callback) {
    _socket.on('incident', callback);
  }

  void subscribeToSync(Function(dynamic) callback) {
    _socket.on('sync', callback);
  }

  void subscribeToNotifications(Function(dynamic) callback) {
    _socket.on('notification', callback);
  }

  void unsubscribe(String event) {
    _socket.off(event);
  }

  void emit(String event, dynamic data) {
    if (_isConnected) {
      _socket.emit(event, data);
    }
  }

  bool get isConnected => _isConnected;
}