import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/models/incident_model.dart';
import '../../data/repositories/incident_repository.dart';

class IncidentState {
  final List<IncidentModel> incidents;
  final IncidentModel? currentIncident;
  final bool isLoading;
  final String? error;
  final Map<String, int> stats;

  IncidentState({
    this.incidents = const [],
    this.currentIncident,
    this.isLoading = false,
    this.error,
    this.stats = const {},
  });

  IncidentState copyWith({
    List<IncidentModel>? incidents,
    IncidentModel? currentIncident,
    bool? isLoading,
    String? error,
    Map<String, int>? stats,
  }) {
    return IncidentState(
      incidents: incidents ?? this.incidents,
      currentIncident: currentIncident ?? this.currentIncident,
      isLoading: isLoading ?? this.isLoading,
      error: error ?? this.error,
      stats: stats ?? this.stats,
    );
  }
}

class IncidentNotifier extends StateNotifier<IncidentState> {
  final IncidentRepository _repository;

  IncidentNotifier(this._repository) : super(IncidentState());

  Future<void> loadIncidents({String? severity, String? status}) async {
    state = state.copyWith(isLoading: true, error: null);
    
    final response = await _repository.getIncidents(severity: severity, status: status);
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        incidents: response.data!,
        isLoading: false,
      );
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
    }
  }

  Future<IncidentModel?> loadIncidentDetail(String id) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.getIncidentDetail(id);
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        currentIncident: response.data,
        isLoading: false,
      );
      return response.data;
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
      return null;
    }
  }

  Future<bool> createIncident(Map<String, dynamic> data) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.createIncident(data);
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        incidents: [response.data!, ...state.incidents],
        isLoading: false,
      );
      return true;
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
      return false;
    }
  }

  Future<bool> resolveIncident(String id, Map<String, dynamic> resolution) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.resolveIncident(id, resolution);
    
    state = state.copyWith(isLoading: false);
    
    if (response.isSuccess) {
      await loadIncidents();
      return true;
    }
    
    state = state.copyWith(error: response.message);
    return false;
  }

  Future<void> loadStats() async {
    final response = await _repository.getIncidentStats();
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(stats: response.data);
    }
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}

final incidentProvider = StateNotifierProvider<IncidentNotifier, IncidentState>((ref) {
  final repository = IncidentRepository();
  return IncidentNotifier(repository);
});