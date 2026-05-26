import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:connectivity_plus/connectivity_plus.dart';

class NetworkState {
  final bool isConnected;
  final ConnectivityResult connectionType;

  NetworkState({
    this.isConnected = true,
    this.connectionType = ConnectivityResult.wifi,
  });

  NetworkState copyWith({
    bool? isConnected,
    ConnectivityResult? connectionType,
  }) {
    return NetworkState(
      isConnected: isConnected ?? this.isConnected,
      connectionType: connectionType ?? this.connectionType,
    );
  }
}

class NetworkNotifier extends StateNotifier<NetworkState> {
  NetworkNotifier() : super(NetworkState()) {
    _init();
  }

  void _init() {
    Connectivity().onConnectivityChanged.listen((result) {
      state = state.copyWith(
        isConnected: result != ConnectivityResult.none,
        connectionType: result,
      );
    });
  }

  void checkConnection() async {
    final result = await Connectivity().checkConnectivity();
    state = state.copyWith(
      isConnected: result != ConnectivityResult.none,
      connectionType: result,
    );
  }
}

final networkProvider = StateNotifierProvider<NetworkNotifier, NetworkState>((ref) {
  return NetworkNotifier();
});