import '../models/compliance_model.dart';
import '../models/api_response_model.dart';
import '../datasources/remote/compliance_api.dart';
import '../../core/utils/logger.dart';

class ComplianceRepository {
  final ComplianceApi _complianceApi;

  ComplianceRepository(this._complianceApi);

  Future<ApiResponseModel<dynamic>> getComplianceDashboard() async {
    try {
      return await _complianceApi.getComplianceDashboard();
    } catch (e) {
      Logger.error('Get compliance dashboard error', e);
      return ApiResponseModel(success: false, message: 'Failed to load compliance dashboard');
    }
  }

  Future<ApiResponseModel<dynamic>> getIso27001Status() async {
    try {
      return await _complianceApi.getIso27001Status();
    } catch (e) {
      Logger.error('Get ISO 27001 status error', e);
      return ApiResponseModel(success: false, message: 'Failed to load ISO 27001 status');
    }
  }

  Future<ApiResponseModel<dynamic>> getGdprStatus() async {
    try {
      return await _complianceApi.getGdprStatus();
    } catch (e) {
      Logger.error('Get GDPR status error', e);
      return ApiResponseModel(success: false, message: 'Failed to load GDPR status');
    }
  }

  Future<ApiResponseModel<dynamic>> getPciDssStatus() async {
    try {
      return await _complianceApi.getPciDssStatus();
    } catch (e) {
      Logger.error('Get PCI DSS status error', e);
      return ApiResponseModel(success: false, message: 'Failed to load PCI DSS status');
    }
  }

  Future<ApiResponseModel<dynamic>> runAudit(String standard) async {
    try {
      return await _complianceApi.runAudit(standard);
    } catch (e) {
      Logger.error('Run audit error', e);
      return ApiResponseModel(success: false, message: 'Failed to run audit');
    }
  }

  Future<ApiResponseModel<void>> uploadEvidence(String controlId, String filePath, String notes) async {
    try {
      return await _complianceApi.uploadEvidence(controlId, filePath, notes);
    } catch (e) {
      Logger.error('Upload evidence error', e);
      return ApiResponseModel(success: false, message: 'Failed to upload evidence');
    }
  }

  Future<ApiResponseModel<dynamic>> getAuditResults({
    String? standard,
    String? status,
    int page = 1,
    int limit = 20,
  }) async {
    try {
      return await _complianceApi.getAuditResults(
        standard: standard,
        status: status,
        page: page,
        limit: limit,
      );
    } catch (e) {
      Logger.error('Get audit results error', e);
      return ApiResponseModel(success: false, message: 'Failed to load audit results');
    }
  }

  Future<ApiResponseModel<String>> exportComplianceReport(String standard, String format) async {
    try {
      return await _complianceApi.exportComplianceReport(standard, format);
    } catch (e) {
      Logger.error('Export compliance report error', e);
      return ApiResponseModel(success: false, message: 'Failed to export report');
    }
  }
}