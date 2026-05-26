import 'dart:io';
import 'package:dio/dio.dart';
import '../api_client.dart';
import '../../models/api_response_model.dart';
import '../../../core/constants/api_constants.dart';

class ComplianceApi {
  final ApiClient _apiClient;

  ComplianceApi(this._apiClient);

  Future<ApiResponseModel<dynamic>> getComplianceDashboard() async {
    final response = await _apiClient.get(ApiConstants.compliance);
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load compliance dashboard',
    );
  }

  Future<ApiResponseModel<dynamic>> getIso27001Status() async {
    final response = await _apiClient.get(ApiConstants.iso27001);
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load ISO 27001 status',
    );
  }

  Future<ApiResponseModel<dynamic>> getGdprStatus() async {
    final response = await _apiClient.get(ApiConstants.gdpr);
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load GDPR status',
    );
  }

  Future<ApiResponseModel<dynamic>> getPciDssStatus() async {
    final response = await _apiClient.get(ApiConstants.pciDss);
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load PCI DSS status',
    );
  }

  Future<ApiResponseModel<dynamic>> runAudit(String standard) async {
    final response = await _apiClient.post(ApiConstants.runAudit, data: {'standard': standard});
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to run audit',
    );
  }

  Future<ApiResponseModel<void>> uploadEvidence(String controlId, String filePath, String notes) async {
    final formData = FormData.fromMap({
      'control_id': controlId,
      'notes': notes,
      'evidence': await MultipartFile.fromFile(filePath),
    });
    
    final response = await _apiClient.dio.post(
      ApiConstants.uploadEvidence,
      data: formData,
      options: Options(
        headers: {'Content-Type': 'multipart/form-data'},
      ),
    );
    
    final data = response.data;
    
    return ApiResponseModel(
      success: data['success'] == true,
      message: data['message'],
    );
  }

  Future<ApiResponseModel<dynamic>> getAuditResults({
    String? standard,
    String? status,
    int page = 1,
    int limit = 20,
  }) async {
    final response = await _apiClient.get(
      '${ApiConstants.compliance}/audit/results',
      queryParams: {
        'page': page,
        'limit': limit,
        if (standard != null) 'standard': standard,
        if (status != null) 'status': status,
      },
    );
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load audit results',
    );
  }

  Future<ApiResponseModel<String>> exportComplianceReport(String standard, String format) async {
    final response = await _apiClient.get(
      '${ApiConstants.compliance}/export',
      queryParams: {'standard': standard, 'format': format},
    );
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data']['url'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to export report',
    );
  }
}