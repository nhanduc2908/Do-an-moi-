import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/repositories/compliance_repository.dart';

class ComplianceState {
  final dynamic dashboardData;
  final dynamic iso27001Status;
  final dynamic gdprStatus;
  final dynamic pciDssStatus;
  final bool isLoading;
  final String? error;

  ComplianceState({
    this.dashboardData,
    this.iso27001Status,
    this.gdprStatus,
    this.pciDssStatus,
    this.isLoading = false,
    this.error,
  });

  ComplianceState copyWith({
    dynamic dashboardData,
    dynamic iso27001Status,
    dynamic gdprStatus,
    dynamic pciDssStatus,
    bool? isLoading,
    String? error,
  }) {
    return ComplianceState(
      dashboardData: dashboardData ?? this.dashboardData,
      iso27001Status: iso27001Status ?? this.iso27001Status,
      gdprStatus: gdprStatus ?? this.gdprStatus,
      pciDssStatus: pciDssStatus ?? this.pciDssStatus,
      isLoading: isLoading ?? this.isLoading,
      error: error ?? this.error,
    );
  }
}

class ComplianceNotifier extends StateNotifier<ComplianceState> {
  final ComplianceRepository _repository;

  ComplianceNotifier(this._repository) : super(ComplianceState());

  Future<void> loadDashboard() async {
    state = state.copyWith(isLoading: true, error: null);
    
    final response = await _repository.getComplianceDashboard();
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        dashboardData: response.data,
        isLoading: false,
      );
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
    }
  }

  Future<void> loadIso27001Status() async {
    final response = await _repository.getIso27001Status();
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(iso27001Status: response.data);
    }
  }

  Future<void> loadGdprStatus() async {
    final response = await _repository.getGdprStatus();
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(gdprStatus: response.data);
    }
  }

  Future<void> loadPciDssStatus() async {
    final response = await _repository.getPciDssStatus();
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(pciDssStatus: response.data);
    }
  }

  Future<bool> runAudit(String standard) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.runAudit(standard);
    
    state = state.copyWith(isLoading: false);
    
    if (response.isSuccess) {
      await loadDashboard();
      return true;
    }
    
    state = state.copyWith(error: response.message);
    return false;
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}

final complianceProvider = StateNotifierProvider<ComplianceNotifier, ComplianceState>((ref) {
  final repository = ComplianceRepository();
  return ComplianceNotifier(repository);
});