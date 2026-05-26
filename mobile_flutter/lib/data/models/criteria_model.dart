import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'criteria_model.freezed.dart';
part 'criteria_model.g.dart';

@freezed
class CriteriaModel with _$CriteriaModel {
  const factory CriteriaModel({
    String? id,
    String? code,
    String? name,
    String? description,
    @JsonKey(name: 'domain_id') String? domainId,
    int? weight,
    @JsonKey(name: 'scoring_method') String? scoringMethod,
    @JsonKey(name: 'max_score') double? maxScore,
    @JsonKey(name: 'min_score') double? minScore,
    @JsonKey(name: 'passing_score') double? passingScore,
    @JsonKey(name: 'evidence_required') bool? evidenceRequired,
    String? status,
    @JsonKey(name: 'created_at') DateTime? createdAt,
    @JsonKey(name: 'updated_at') DateTime? updatedAt,
  }) = _CriteriaModel;

  factory CriteriaModel.fromJson(Map<String, dynamic> json) => _$CriteriaModelFromJson(json);
}

extension CriteriaModelX on CriteriaModel {
  String get scoreRange => '$minScore - $maxScore';
  
  bool get isPassable => (maxScore ?? 0) >= (passingScore ?? 0);
  
  bool get requiresEvidence => evidenceRequired == true;
}